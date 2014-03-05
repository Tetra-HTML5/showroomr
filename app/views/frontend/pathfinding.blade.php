@extends('frontend.template')

@section('head')
<style type="text/css">
	.canvas {
		max-width: 100%;
		height: auto;
		border: 2px solid black;
	}
</style>
<script type="text/javascript" src="{{ asset('assets/js/modernizr-1.6.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/pathfinding-browser.js')}}"></script>

<script type="text/javascript">
	
	window.addEventListener("load", eventWindowLoaded, false);

    var img,cw,ch,p;
	var canvas, context;
	var startX,startY ,floorStart,eindPos ;
	var cell,gridWidth,gridHeight;
	var matrix, grid ;
	var shortestPath;
	var endx ,endy
	
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
		canvasApp();
	}

	function canvasSupport() {
		return Modernizr.canvas;
	}

	function canvasApp() {

		if (!canvasSupport()) {
			return;
		}

		img = new Image();
		img.onload = loader;
		img.src = "{{$floor->floor_map}}";

	}

	function loader() {
		imagey = this.height;
		imagex = this.width;

        console.log(imagex, imagey);

		drawCanvas();
	}

	function drawCanvas() {
		
		// set canvas dimensions
		cw = imagex;
		ch = imagey;
		p = 0;

		// canvas
		canvas = document.getElementById("canvas");
		canvas.height = imagey;
		canvas.width = imagex;
		context = canvas.getContext("2d");

		//start and end position
		startX = {{ $startPoint["x"] }};
		startY = {{ $startPoint["y"]}};
		floorStart={{ $startPoint["floor"]}};
		eindPos ={{ $possibilities}};
		
		// calculate the size of the grid, and provide cell size
		cell = 10;
		gridWidth = Math.floor(imagex / cell) + 1;
		gridHeight = Math.floor(imagey / cell) + 1;
		
		@if( $floor->floor_grid != null)
			matrix = {{ $floor->floor_grid }};
		@else
		  matrix = null;
		@endif
			
		if(startX > gridWidth || startY > gridHeight || matrix == null){
			Debugger.log("foute input waarde of geen geen gevonden");
			$("#popupError").slideDown();
			document.getElementById("canvas").style.display = 'none';
		} else {
			
			// make grid
			//matrix = {{ $floor->floor_grid }};
			grid = new PF.Grid(gridWidth, gridHeight, matrix);
	
			//call checkEndPosition, get the shortest path back
			shortestPath = checkEndPosition(eindPos, floorStart);
			// if path is undefined (this can happen when the product isn't reachable) show an error.
			if ( typeof shortestPath == 'undefined') {
				Debugger.log("undefined path, niet bereikbaar");
				$("#popupError").slideDown();
				document.getElementById("canvas").style.display = 'none';
			}else{
				// call drawMap  so map is always shown
				drawMap(shortestPath);
			}
			//color the endPosition
			endx = 0;
			endy = 0;
		}
	} // end DrawCanvas

			//checkEndPosition, checks what the shortest path is out of all possible and positions and returns this 
			function checkEndPosition(pos, floor) {
				var prevlen = 0;
				var reachablePos = new Array();
				var path, len, shortestpath;
				//if there are possible endpoints
				if ( typeof pos !== 'undefined' && pos.length > 0) {
					//loop through array and take only the coordinates on the same floor
					for ( i = 0; i < pos.length; i++) {
						if (pos[i][2] == floor) {
							reachablePos.push([pos[i][0], pos[i][1], pos[i][2]]);
						}
					}
					// if there are positions on this floor
					if (reachablePos.length > 0) {
						//loop through array and get every path possible
						for ( i = 0; i < reachablePos.length; i++) {
							//call getPath
							path = getPath(reachablePos[i][0], reachablePos[i][1]);
							len = path.length;
							if (len != 0 && (prevlen == 0 || prevlen > len)) {
								prevlen = len;
								shortestpath = path;
								endx = reachablePos[i][0];
								endy = reachablePos[i][1];
							}
						}
					} else {
						// the product is found on another floor , error will be displayed
						shortestpath = 0;
						$("#popupOtherFloor").slideDown();
						document.getElementById("canvas").style.display = 'none';
					}
					return shortestpath;
				} else {
					// if the product didn't have any position an error is shown
					Debugger.log("geen positie van product gekend");
					$("#popupError").slideDown();
					document.getElementById("canvas").style.display = 'none';
				}
			}//end checkEndPosition

			//make a clone so we can reuse the original grid
			// use Astar as a finder
			function getPath(endX, endY) {
				// call setWalkable, the endpoints must be walkable to calculate the path
				setWalkable(endX, endY, true);
	
				var gridclone = grid.clone();
				var finder = new PF.AStarFinder({
					allowDiagonal : true
				});
				var path = finder.findPath(startX, startY, endX, endY, gridclone);
				
				setWalkable(endX, endY, false);
	
				return path;
	
			}//end getPath

			//setWalkable
			function setWalkable(endX, endY, isWalkable) {
				var walkable = grid.isWalkableAt(endX, endY);
				if (!walkable) {
					grid.setWalkableAt(endX, endY, isWalkable);
				}
			}//end setWalkable

			//drawMap fucntion
			function drawMap(path) {
	
	
				context.drawImage(img, 0, 0);
	
				//show the start and end positions
				drawStartAndEnd(startX, startY, endx, endy);
				
				//draw the calculated path
				context.beginPath();
				for (var i = 0; i < path.length - 1; i++) {
					x = path[i][0];
					y = path[i][1];
					nextX = path[i+1][0];
					nextY = path[i+1][1];
					context.moveTo(x * cell + cell / 2, y * cell + cell / 2);
					context.lineTo(nextX * cell + cell / 2, nextY * cell + cell / 2);
				}
				context.strokeStyle = "blue";
				context.lineWidth = 5;
				context.lineCap = 'round';
				context.stroke();
				context.closePath();
	
			}//end drawMap

		function drawStartAndEnd(xcoord, ycoord, endx, endy) {

			var xOffset, yOffset;

			var imgStart = new Image();
			imgStart.onload = function() {
				context.drawImage(imgStart, (xcoord * cell) + xOffset, (ycoord * cell) + yOffset);
			};
			if (xcoord > endx) {
				imgStart.src = "{{asset('assets/img/')}}/" + 'running_man_back.png';
				xOffset = 2, yOffset = -20;
			} else {
				imgStart.src = "{{asset('assets/img/')}}/" + 'running_man.png';
				xOffset = -16, yOffset = -18;
			}

			var imgEnd = new Image();
			imgEnd.onload = function() {
				context.drawImage(imgEnd, (endx * cell) + 2, (endy * cell) - 18);
			};
			imgEnd.src = "{{asset('assets/img/')}}/" + 'finish.png';

		}//end drawStartAndEnd
	var imagex, imagey;

</script>

@endsection

@section('content')
<h3>{{$product->prod_name}}</h3>

<div class="ui-corner-all"  id="popupOtherFloor"  style="display:none;">
  <div class="ui-bar ui-bar-d">
    <h1>Whoopsie!</h1>
  </div>
  <div class="ui-body ui-body-d">
    <h3 class="ui-title">Product niet gevonden op deze verdieping!</h3>
		<p>
			Gelieve je te verplaatsen naar verdieping
			@if(count($otherFloors)>0)
			@foreach($otherFloors as $floor)
			{{$floor}},
			@endforeach
			@endif
			om de dichtsbijzijnde code te scannen!
		</p>
		<a href="{{$previousUrl}}" class="ui-btn ui-corner-all ui-shadow ui-btn-inline"  data-icon="back">Terug</a>
		<a href="{{url('route/scan/')}}" class="ui-btn ui-corner-all ui-shadow ui-btn-inline"  data-icon="refresh">Opnieuw scannen</a>

  </div>
</div>

<div class="ui-corner-all"  id="popupError"  style="display:none;">
  <div class="ui-bar ui-bar-d">
    <h1>Whoopsie!</h1>
  </div>
  <div class="ui-body ui-body-d">
    <h3 class="ui-title">Er is iets misgegaan!</h3>
		<p>
			Gelieve dit probleem te melden aan de infobalie.
		</p>
		<a href="{{$previousUrl}}" class="ui-btn ui-corner-all ui-shadow ui-btn-inline"  data-icon="back">Terug</a>

  </div>
</div>

</br>
<canvas class="canvas" id="canvas" width="400px" height="400px" style="background: #fff; "></canvas>

@endsection