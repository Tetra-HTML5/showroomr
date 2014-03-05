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
<script type="text/javascript" src="{{ asset('assets/js/kinetic-v4.7.4.min.js')}}"></script>
<!-- ######## code backend map #############################
	<script type="text/javascript">
window.addEventListener("load", eventWindowLoaded, false);	

var Debugger = function () { };
Debugger.log = function (message) {
	try {
		console.log(message);
	} catch (exception) {
		return;
	}
}

function eventWindowLoaded () {
	canvasApp();
}

function canvasSupport () {
  	return Modernizr.canvas;
}

function canvasApp () {
  		
  		if (!canvasSupport()) {
			 return;
  		}
  			
  		//canvas
		var theCanvas = document.getElementById("canvasMap");
		var context = theCanvas.getContext("2d");
		//radius circle
		var radius = 10;
		//coordinates
		var xcoord = 0;
		var ycoord = 0;
			
		
		Debugger.log("Drawing Canvas");
		
		// button
		var btnsize = document.getElementById("btn_size");
		btnsize.addEventListener("click", getButtonCoord);
		
		// set coordinates when clicked on canvas 
		theCanvas.onclick = function(ev){
		pos = getMouse( ev, theCanvas);
		xcoord= pos.x;
		ycoord= pos.y;
		drawScreen();
		//Debugger.log("click"+ xcoord);
		}
		
    
    	// eventlistener mousemovement
		theCanvas.addEventListener('mousemove', function(evt) {
			// calls function to get coordinates 
        	var mousePos = getMouse( evt, theCanvas);
        	//show coordinates on screen
        	var message = 'Mouse position: ' + mousePos.x + ', ' + mousePos.y;
        	document.getElementById("xycoordinates").innerHTML=message;
        	drawScreen();
        }, false);

		// handles the coordinates from the input when button is clicked
		function getButtonCoord(){
			//inputted values by button
  			xcoord = document.getElementById("xcoord");
			ycoord = document.getElementById("ycoord");
			xcoord = parseInt(xcoord.value);
			ycoord = parseInt(ycoord.value);
			drawScreen();
		}
		
		//drawscreen function
  		function drawScreen() {
  			// make image
			var img = new Image();
			var source = new String("{{asset('maps/testkamer.jpg')}}");
			img.src = source;
			//onload draw image and pinpoint.
			img.onload = function(){
				context.drawImage(img,0,0);
				context.beginPath();
      			context.arc(xcoord, ycoord, radius, 0, 2 * Math.PI, false);
      			context.fillStyle = 'green';
      			context.fill();
      			context.lineWidth = 5;
      			context.strokeStyle = '#003300';
      			context.stroke(); 
			}
		}
		
    //zie oef 9 canvasaction van in de les  ==  source below for mouse position is from http://stackoverflow.com/users/154112/simon-sarris
	// needed for to offset
   	var html = document.documentElement.parentNode;
   	offsetX = html.offsetTop;
  	offsetY= html.offsetLeft;
   
   //getmouse function 
	function getMouse(e, canvas) {
  		var element = canvas, offsetX = 0, offsetY = 0, mx, my;

  		// Compute the total offset. It's possible to cache this if you want
  		if (element.offsetParent !== undefined) {
    		do {
      			offsetX += element.offsetLeft;
      			offsetY += element.offsetTop;
    		} while ((element = element.offsetParent));
  		}
	
  		mx = e.pageX - offsetX;
  		my = e.pageY - offsetY;
  		
  		// We return a simple javascript object with x and y defined
  		return {x: mx, y: my};
  		
	}

		drawScreen();	
}

   
</script> -->

@endsection

@section('content')
<input  type="text" name="x" id="xcoord"  value="">									
<input  type="text" name="y" id="ycoord"  value="">
<input type="submit" type="button" id="btn_size" value="set coordinates"/>
<!-- ############### backend map  ############
	<div id="xycoordinates"></div>
<div >
<canvas class="canvas" id="canvasMap" width="1000" height="751">
 Your browser does not support HTML 5 Canvas. 
</canvas> 
</div> -->

<!--     #######     frontend code         #######-->
<div id="container"></div>
<script >

// debugger function
	var Debugger = function () { };

	Debugger.log = function (message) {
		try {
			console.log(message);
		} catch (exception) {
			return;
		}
	}
	
// this code was found on
// tutorial : http://www.html5canvastutorials.com/labs/html5-canvas-multi-touch-scale-stage-with-kineticjs/
// code was added and modified
      	
      	var lastDist = 0 ;
      	//var startScale = 1;
	
	// start position circle
	@if ( isset($coordinates) && $coordinates['x']!=null && $coordinates['y'] != null) // test to see if coordinates are set
		var xcoord =  {{$coordinates['x']}};
		var ycoord =  {{$coordinates['y']}};
	@else 
		var xcoord =  15;// "info desk" on map=> product is in stock but has no coordinates 
		var ycoord =  15; 
	@endif
	
      // stage: draggable: true, makes the full image draggable
      var stage = new Kinetic.Stage({
        container: 'container',
        width: 1000,
        height: 751,
        draggable: true,
       
      });
	
	// layer
      var layer = new Kinetic.Layer();
      
      // this is the dot that points out a location
      // this is an kinetic circle object
      var circle = new Kinetic.Circle({
        x: xcoord,
        y: ycoord,
        radius: 10,
        fill: 'red',
        stroke: 'black',
        strokeWidth: 3
      });
      
  	// the map
  	// image object
      var imageObj = new Image();
      // source of the image
      var source = new String("{{asset('maps/testkamer.jpg')}}");
      // set the source to the image
      imageObj.src = source;
    
   	//onload function
      imageObj.onload = function() {
      	// make en kinetic image of the map
       var map = new Kinetic.Image({
          x: 0,
          y: 0,
          image: imageObj,
          width: 1000,
          height: 751
        });
        // add the shape to the layer
        layer.add(map);
        
        layer.add(circle);

        // add the layer to the stage
        stage.add(layer);
      };
      
	// calculates the distance for zooming
      function getDistance(p1, p2) {
        return Math.sqrt(Math.pow((p2.x - p1.x), 2) + Math.pow((p2.y - p1.y), 2));
      }
      
      // touchmove event gets called when user touches screen 
      stage.getContent().addEventListener('touchmove', function(evt) {
        var touch1 = evt.touches[0];
        var touch2 = evt.touches[1];
		
		// if touched with 2 fingers, used to zoom than call the getdistance function
        if(touch1 && touch2) {
          var dist = getDistance({
            x: touch1.clientX,
            y: touch1.clientY
          }, {
            x: touch2.clientX,
            y: touch2.clientY
          });
		
		// in case lastDist is not initialized than it will initialize it with the value of dist
          if(!lastDist) {
            lastDist = dist;
          }
			//needed to scale the stage, calculates with waht scale
          var scale = stage.getScale().x * dist / lastDist;
			// set the new scale
          stage.setScale(scale);
          //draw
          stage.draw();
          //set the dist to the variable lastdist
          lastDist = dist;
        }
      }, false);

	// zorgt ervoor dat wanneer men zoomed dat de scale niet steeds terug de originale grootte springt maar de nieuwe grootte behoudt
      stage.getContent().addEventListener('touchend', function() {
        lastDist = 0;
      }, false);

  	// button
		var btnsize = document.getElementById("btn_size");
		btnsize.addEventListener("click", getButtonCoord);
		// function to handle button
		function getButtonCoord(){
			//inputted values by button
			//Debugger.log("clicked in kinetic");
  			xcoord = document.getElementById("xcoord");
			ycoord = document.getElementById("ycoord");
			xcoord = parseInt(xcoord.value);
			ycoord = parseInt(ycoord.value);
			
			// add the shape to the layer so it is shown
        	layer.add(circle);
			// needed to give the circle it's new coordinates
			//"Tweens enable you to animate a node between the current state and a new state" (kineticJS docs)      
			var tween = new Kinetic.Tween({
        	node: circle,
        	duration: 1,
        	x: xcoord,
        	y: ycoord,
        	opacity: 1,
   			}).play();
		}
   
</script>

@endsection