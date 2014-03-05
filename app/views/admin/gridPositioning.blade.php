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
    updateCurrentFloor();

function updateCurrentFloor(){
    $('#currentFloor').val($('#floors option:selected').val());
}

var Debugger = function() {
};
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
    $('#load').click(function(){
        img = null;
        grid = null;
        canvas_width = null;
        canvas_height = null;
        canvas = null;
        updateCurrentFloor();
        drawCanvas();
    })

function drawCanvas()
{   
    var canvas, context;
    var floorId = $('#floors option:selected').val();

    $.ajax({
            url : '{{ url("admin/positioning/floor") }}/' + floorId,
            type : 'GET',
            dataType : 'json',
            success : function(json){
                console.log(json);

                img = new Image();
                img.src = json.floor_map;
                $('canvas').css('background-image', 'url('+json.floor_map+')');

                canvas = document.getElementById("canvas");
                context = canvas.getContext("2d");

                img.onload = function(){
                    // get image dimensions
                    canvas_width = img.width;
                    canvas_height = img.height;

                    //set canvas dimensions
                    canvas.height = canvas_height;
                    canvas.width = canvas_width;
                    console.log("image loaded");

                    grid_width = Math.floor(canvas_width / 10) + 1 ;
                    grid_height = Math.floor(canvas_height / 10) + 1; 

                    if(json.floor_grid != null){
                        gridArray = JSON.parse(json.floor_grid);
                        grid = new PF.Grid(grid_width,grid_height, gridArray);
                    } else {
                        grid = new PF.Grid(grid_width,grid_height);
                    }

                    drawWalls(); 
                    drawGrid();
                }

                canvas.onmousedown = function(ev){
                    var position = getMouse(ev, canvas);
                    xcoord = Math.floor(position.x / cell_size);
                    ycoord = Math.floor(position.y / cell_size);
                    startWalkable = walkable = grid.isWalkableAt(xcoord, ycoord);  //true if walkable, false if obstacle

                    if (walkable) {  //when walkable and clicked, make it an obstacle
                        grid.setWalkableAt(xcoord, ycoord, false);
                    } else { //when an obstacle and clicked, make it walkable
                        grid.setWalkableAt(xcoord, ycoord, true);
                    }

                    document.onmousemove = function(ev) {
                            var position = getMouse(ev, canvas);
                            xcoord = Math.floor(position.x / cell_size);
                            ycoord = Math.floor(position.y / cell_size);
                            console.log(startWalkable);

                            if (startWalkable) {  //when walkable and clicked, make it an obstacle
                                grid.setWalkableAt(xcoord, ycoord, false);
                            } else { //when an obstacle and clicked, make it walkable
                                grid.setWalkableAt(xcoord, ycoord, true);
                            }

                            //draw all the walls
                            drawWalls();
                            //redraw the grid
                            drawGrid();
                    }

                    this.onmouseup = function() {
                        document.onmousemove = null
                    }

                    //draw all the walls
                    drawWalls();

                    //redraw the grid
                    drawGrid();
                }
                drawGrid();
            }
    });

    // size of a cell
    var cell_size = 10;

    var save = document.getElementById("save");
    save.onclick = function(ev){
        var nodes = grid.nodes;
        var array = new Array(grid.height);
        // Walk trough each array (row)
        for(var i = 0; i < nodes.length; i++){            
            var row = nodes[i];
            // Instantiate row in target array
            array[i] = new Array(grid.width);
            // Loop through columns
            for(var col = 0; col < grid.width; col++){
                // Inverse boolean, to load the grid true represents 'blocked'
                array[i][col] = row[col].walkable ? 0 : 1;
            }
        }

        $('button').attr('disabled', 'disabled');

        $.ajax({
        url : '{{ url("admin/positioning/floor") }}/' + $('#currentFloor').val(),
        type : 'POST',
        dataType : 'json',
        data : { floorGrid : JSON.stringify(array)}, // Stringify (array to json)
        success : function(json){
            if(json.success){
                $('#success').slideDown().delay(3000).slideUp();
            } else {
                $('#fail').slideDown().delay(3000).slideUp();
            }
            $('button').removeAttr('disabled');
        }});
    }

  

    function drawWalls()
    {
        for(var i = 0; i < grid.width; i++)
        {
            for(var j=0 ; j < grid.height; j++)
            {
                var walkable = grid.isWalkableAt(i, j);
                if (walkable) {
                    context.clearRect(i * cell_size, j * cell_size, cell_size, cell_size);
                } else {
                    context.fillStyle = "rgb(225,0,0)";
                    context.fillRect(i * cell_size, j * cell_size, cell_size, cell_size);
                }
            }
        }
    }

    //drawGrid function
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

}
var image_height,image_width;

});

</script>

<div class="container">
    <br/><br/>
<p>
    <select id="floors" class="form-control">
        @foreach ($floors as $floor)
            <option value="{{$floor->floor_id}}">{{$floor->establishments->est_name}} {{$floor->floor_level}}</option>
        @endforeach
    </select>
    <input type="hidden" id="currentFloor" />
</p>
    <p>
    <button class="btn" id="load"><span class="glyphicon glyphicon-open"></span> Laden</button>
    <button type="button" id="save" class="btn"><span class="glyphicon glyphicon-save"></span> Raster opslaan</button>
</p>
    <div class="alert alert-success" id="success"  style="display:none;">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
        Het raster is succesvol opgeslagen
    </div>
    <div class="alert alert-danger" style="display:none;" id="fail">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
        Er ging iets mis bij het opslaan van het raster
    </div>
<br/>
<canvas id="canvas" width="400px" height="600px"></canvas>
</div>
@endsection

@stop