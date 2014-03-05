@extends(Config::get('syntara::views.master'))

@section('content')

<script type="text/javascript" src="{{ asset('assets/js/modernizr-1.6.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/pathfinding-browser.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/json2.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){
    window.addEventListener("load", eventWindowLoaded, false);

    var img = null;
    var grid = null;
    var canvas_width = null;
    var canvas_height = null;
    var canvas, context;
    var cell_size = 10;
    var image_height, image_width;
    updateCurrentFloor();
    var createArray = new Array();
    var deleteArray = new Array();
    var locations;
    var productSelected;
    var changesMade;
    var prodIdDelete;
    var locationData;

    var Debugger = function() {};

    Debugger.log = function(message) {
        try {
            console.log(message);
        } catch (exception) {
            return;
        }
    }

    function eventWindowLoaded() {
        wallBuilder();
    }

    function canvasSupport() {
        return Modernizr.canvas;
    }

    function wallBuilder() {

        if (!canvasSupport()) {
            return;
        }
        drawCanvas();
    }

    function checkButton() {
        changesMade = createArray.length > 0 || deleteArray.length > 0;
        if(changesMade) {
            document.getElementById('save').disabled = false;
            document.getElementById('load').disabled = true;
            document.getElementById('revert').disabled = false;
        }
        else{
            document.getElementById('save').disabled = true;
            document.getElementById('load').disabled = false;
            document.getElementById('revert').disabled = true;
        }
    }

    $('#revert').click(function(){
        createArray = [];
        deleteArray = [];
        checkButton();
        processCoordinates(locationData);
    });

    function updateCurrentFloor() {
        $('#currentFloor').val($('#floors option:selected').val());
    }

    $('#load').click(function() {
        img = null;
        grid = null;
        canvas_width = null;
        canvas_height = null;
        canvas = null;
        updateCurrentFloor();
        $('#searchButton').trigger('click');
        drawCanvas();
    });

    $('#save').click(function(){
        var prodId = parseInt($('#products a.active').attr('data-id'));
        var floorId = $('#currentFloor').val();
        $('button').attr('disabled', 'disabled');

        $.ajax({
            url : '{{url("admin/positioning/products/save")}}',
            type : 'POST',
            dataType : 'json',
            success : function(json){
                if(json.success){
                    createArray = [];
                    deleteArray = [];

                    // Only disable the buttons which has to be disabled
                    $('button').removeAttr('disabled');
                    $('#deleteButton').attr('disabled', 'disabled');
                    checkButton();

                    // Reload coordinates
                    loadCoordinates(prodId, floorId, processCoordinates);
                } else {
                    // Show erorr
                }
            },
            data : {
                deleteArr : deleteArray,
                createArr : createArray,
                productId : prodId
            }
        });


    })

    function drawCanvas() {
        var floorId = $('#floors option:selected').val();
        $.ajax({
            url : '{{ url("admin/positioning/floor") }}/' + floorId,
            type : 'GET',
            dataType : 'json',
            success : function(json) {

                img = new Image();
                img.src = json.floor_map;
                $('canvas').css('background-image', 'url(' + json.floor_map + ')');

                canvas = document.getElementById("canvas");
                context = canvas.getContext("2d");

                img.onload = function() {
                    // get image dimensions
                    canvas_width = img.width;
                    canvas_height = img.height;

                    //set canvas dimensions
                    canvas.height = canvas_height;
                    canvas.width = canvas_width;
                    console.log("image loaded");

                    grid_width = Math.floor(canvas_width / 10) + 1;
                    grid_height = Math.floor(canvas_height / 10) + 1;

                    if (json.floor_grid != null) {
                        gridArray = JSON.parse(json.floor_grid);
                        grid = new PF.Grid(grid_width, grid_height, gridArray);
                    } else {
                        grid = new PF.Grid(grid_width, grid_height);
                    }

                    drawWalls();
                    drawGrid();
                }

               productSelected = false;

                canvas.onmousedown = function(ev) {
                    if (productSelected) {
                        var position = getMouse(ev, canvas);
                        xcoord = Math.floor(position.x / cell_size);
                        ycoord = Math.floor(position.y / cell_size);
                        fill("green", xcoord, ycoord);
                        var exists = isAlreadyCreated(xcoord, ycoord, floorId);
                        if (!exists) {
                            addPoint(xcoord, ycoord, floorId);
                        } else {
                            $('#alreadyExists').slideDown().delay(3000).slideUp();
                        }
                    } else {
                        $('#noProduct').slideDown().delay(3000).slideUp();
                    };

                    this.onmouseup = function() {
                        document.onmousemove = null
                    }
                }//on onmouse down
                drawGrid();
            }
        });
    }

    function addPoint(x, y, floorId){
    	createArray.push([x, y, floorId]);

    	    $link = $('<a/>', {
                'data-x' : x,
                'data-y': y,
                'style' : 'padding-top:2px; padding-bottom:2px; display:none;',
                'class' : 'list-group-item new-point',
                'href' : '#'
            });
            $link.html(x +', ' + y);
            $('#coordinates').append($link);
            $link.slideDown();

            checkButton();
    }

    //delete the selected coordinates
	$('#deleteButton').click(function() {
		$active = $('#coordinates a.active');
		if($active.hasClass('new-point')){
			// Recently added point, delete from create array
			var x = parseInt($active.attr('data-x'));
			var y = parseInt($active.attr('data-y'));
			var floorId = $('#currentFloor').val();

			for(var i = 0; i < createArray.length; i++){
				array = createArray[i];
				// If the 1st, 2nd and 3rd array items match with x, y and floorID, then this item has to be deleted
				if(array[0] == x && array[1] == y && array[2] == floorId){
					createArray.splice(i, 1);
				}
			}
		} else {
			// Point already existed, push ID in deleteArray
			deleteArray.push(parseInt($active.attr('data-id')));
		}

		// Remove from listview and disable the button
		$active.slideUp(200, function(){
			$(this).remove();
		});
		$(this).attr('disabled', 'disabled');

        // Redraw coordinates
        drawCoordinates(locationData);
		
		checkButton();
		return false;
	});

    function isAlreadyCreated(xcoord, ycoord, floorId){
        for(var i = 0; i < createArray.length; i++){
            if(createArray[i][0]== xcoord && createArray[i][1]== ycoord  && createArray[i][2]== floorId){
                return true;
            }
        }
        for(var j = 0; j < locations.length; j++){
        	if(locations[j].ppe_xvalue== xcoord && locations[j].ppe_yvalue== ycoord ){
                return true;
            }
        }
        return false;
    }

    function drawWalls() {
        for (var i = 0; i < grid.width; i++) {
            for (var j = 0; j < grid.height; j++) {
                var walkable = grid.isWalkableAt(i, j);
                if (walkable) {
                    context.clearRect(i * cell_size, j * cell_size, cell_size, cell_size);
                } else {
                    context.fillStyle = "rgb(50,50,50)";
                    context.fillRect(i * cell_size, j * cell_size, cell_size, cell_size);
                }
            }
        }
    }

    function drawGrid() {
        // horizontal lines
        context.beginPath();
        for (var x = 0; x <= canvas_width; x += cell_size) {
            context.moveTo(x, 0);
            context.lineTo(x, canvas_height);
        }
        // vertical lines
        for (var x = 0; x <= canvas_height; x += cell_size) {
            context.moveTo(0, x);
            context.lineTo(canvas_width, x);
        }
        context.strokeStyle = "rgba(0,0,0,0.1)";
        context.lineWidth = 1;
        context.stroke();
        context.closePath();
    }

    //getmouse function
    //get the position of the mouse click
    function getMouse(e, canvas) {
        var element = canvas, offsetX = 0, offsetY = 0, mouse_x, mouse_y;
        if (element.offsetParent !== undefined) {
            do {
                offsetX += element.offsetLeft;
                offsetY += element.offsetTop;
            } while ((element = element.offsetParent));
        }
        mouse_x = e.pageX - offsetX;
        mouse_y = e.pageY - offsetY;
        return {
            x : mouse_x,
            y : mouse_y
        };
    }

    //to color the cell
    function fill(color, xcoord, ycoord) {
        context.fillStyle = color;
        context.fillRect(xcoord * cell_size, ycoord * cell_size, cell_size, cell_size);
    }


    $('#searchButton').click(function(){
        checkButton();
        if(changesMade){
            $('#changesMade').slideDown().delay(3000).slideUp();
            return false;
        }

    	$this = $(this);
        $this.attr('disabled', 'disabled');
        $.ajax({
            url: "{{ url('admin/positioning/products/get')}}",
            dataType : 'JSON',
            type : 'post',
            data : {
                search : $('input[name=search]').val(),
                floorId : $('#currentFloor').val()
            },
            success : function(data){
                $('#coordinates').html('');
                $('#products').html('');
                for(var index in data){
                    var link = "<a href='#' data-id='"+index+"' style='padding-top:2px; padding-bottom:2px;' class='list-group-item'>"+data[index]+"</a>";
                    $('#products').append(link);
                }
                $this.removeAttr('disabled');
            }
        });
    });

    $('#searchButton').trigger('click');
    checkButton();

    $('#products').on('click', 'a', function(){
        checkButton();
        if(changesMade){
            $('#changesMade').slideDown().delay(3000).slideUp();
            return false;
        }

        $('#products a.active').removeClass('active');
        $(this).addClass('active');

        var productId = $(this).attr('data-id');
        var floorId = $('#currentFloor').val();
        loadCoordinates(productId, floorId, processCoordinates);
        
        //making shure the 'delete'-button is disabled when no coordinates are selected
		document.getElementById('deleteButton').disabled = true;
		return false;
    });

    $('#coordinates').on('click', 'a', function(){
        $('#coordinates a.active').removeClass('active');
        $(this).addClass('active');
        
        //getting the id, we might need it if we want to delete these coordinates
        prodIdDelete = $(this).attr('data-id');

        //Show it on the map
        x = $(this).attr('data-x');
        y = $(this).attr('data-y');

        highlightMap(x,y);
        
        //when a coordinate is selected, the user can delete them
		document.getElementById('deleteButton').disabled = false;

		checkButton();
		return false;
    });

    function highlightMap(x, y)
    {
        drawWalls();
        drawGrid();
        drawCoordinates(locationData);

        context.fillStyle = "rgb(0,191,255)";
        var imageObj = new Image();
        context.fillRect(x * cell_size, y * cell_size, cell_size, cell_size);

        imageObj.onload = function() {
            context.drawImage(imageObj, (x * cell_size) - 2  , (y * cell_size) - 20 );
            console.log('pinpoint drawn');
        };
        imageObj.src = "{{asset('assets/img/pinpoint.png')}}";

    }

    function loadCoordinates(productIdVal, floorIdVal, callback){
        $.ajax({
            url: "{{ url('admin/positioning/products/coordinates')}}",
            dataType : 'JSON',
            type : 'post',
            data : {
                productId : productIdVal,
                floorId : floorIdVal
            },
            success : callback
        });
    }

    function processCoordinates(data){
       locationData = data;
       locations = data.currentFloor;
        $('#coordinates').html('');
        for(var i = 0; i < locations.length; i++){
            var x = locations[i].ppe_xvalue;
            var y = locations[i].ppe_yvalue;

            $link = $('<a/>', {
                'data-id' : locations[i].id,
                'data-x' : x,
                'data-y': y,
                'style' : 'padding-top:2px; padding-bottom:2px;',
                'class' : 'list-group-item',
                'href' : '#'
            });
            $link.html(x +', ' + y);
            $('#coordinates').append($link);            
        }

        drawCoordinates(locationData);

        productSelected = true;
    }

    function drawCoordinates(data){
        drawWalls();
        drawGrid();
        locations = data.currentFloor;
        for(var i = 0; i < locations.length; i++){
            var x = locations[i].ppe_xvalue;
            var y = locations[i].ppe_yvalue;
            var id = locations[i].id;
            
            // If ID is not in delete array, the product can be shown on the map
            if(deleteArray.indexOf(id) == -1){
                //show the clicked product
                fill("green",x,y);
            }
        }

        for(var i = 0; i < createArray.length; i++){
            var x = createArray[i][0];
            var y = createArray[i][1];
            fill("green", x, y);
        }




    }
});

</script>

<div class="container">
	<br/>
	<p>
		<select id="floors" class="form-control">
			@foreach ($floors as $floor)
			<option value="{{$floor->floor_id}}">{{$floor->establishments->est_name}} {{$floor->floor_level}}</option>
			@endforeach
		</select>
		<input type="hidden" id="currentFloor" />
	</p>
	<p>
		<button class="btn" id="load">
			<span class="glyphicon glyphicon-open"></span> Laden
		</button>

		<button class="btn" id="save">
			<span class="glyphicon glyphicon-save"></span> Opslaan
		</button>

        <button class="btn" id="revert">
            <span class="glyphicon glyphicon-remove-circle"></span> Wijzigingen ongedaan maken
        </button>    

	</p>
	<div class="alert alert-success" id="alreadyExists"  style="display:none;">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
		Plaats bestaat al
	</div>
	<div class="alert alert-danger" style="display:none;" id="noProduct">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
		Er is geen product geselecteerd
	</div>
    <div class="alert alert-danger" style="display:none;" id="changesMade">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
        Er zijn wijzigingen aangebracht, sla eerst op of maak de wijzigingen ongedaan
    </div>
	<br/>
		<div class="row">
			<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading">Producten</div>
					<div class="panel-body">
						<div class="input-group">
							<input type="text" class="form-control input-sm" name="search">
							<span class="input-group-btn">
								<button class="btn btn-default" id="searchButton" type="button" style="padding:8px;"><span class="glyphicon glyphicon-search"></span> Zoek</button>
							</span>
						</div>
                        <small>Maximum 10 producten worden getoond, zoek voor specifiekere resultaten.</small>
					</div>
					<div class="list-group" id="products">
						&nbsp;
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading">Co&ouml;rdinaten</div>
					<div class="list-group" id="coordinates">
					</div>
					<button class="btn btn-default" id="deleteButton" disabled type="button" style="width: 100%"><span class="glyphicon glyphicon-trash"></span> Delete</button>
				</div>
			</div>
        </div>
			<canvas id="canvas" width="400px" height="600px"></canvas>
	</div>
        @endsection
        @stop