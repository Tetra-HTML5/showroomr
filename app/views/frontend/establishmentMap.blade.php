@extends('frontend.template')

@section('content')
  
        
		
		@if (count($floors) == 0)
			<div class="exclamation">
				!s
			</div>		
			<div class="exclamation_text">
				<p> Er zijn momenteel geen plattegronden</p>
			</div>
		@else 
		
			@if (count($floors) == 1)
			
				<div id="title">				
				</div>
				<img id="afbeelding" style="position:absolute; width:100%; margin-left:-16px; "/>
			
			@else
				
				<div data-role="fieldcontain" id="select">
		        	{{ Form::open() }}
		            	<select name="establishment" id="select-choice-a"  data-native-menu="false">
		            		@foreach($floors as $floor)
		            			<option value="{{$floor->floor_id}}">{{$floor->floor_level}}</option>
		            		@endforeach
				        </select>	
				    {{ Form::close() }}
				</div>
				
				<img id="afbeelding" style="position:absolute; width:100%; margin-left:-16px; "/>		
			@endif
		@endif
		
		



<script type="text/javascript">
	$(document).ready(function(){
		var floors = {{$floors->toJson()}};
		
		//when there is only 1 floor on this establishment, there is no need to show a dropdown
		//we only show the map of the present floor 
		if (floors.length == 1) {
			
			//the first and only floor available
			var floor = floors[0];
			
			var id = floor.floor_id;
			
			$('#afbeelding').attr('src', floor.floor_map);
			
			//add the title above the image
			$title = $('<h1>');
			$title.html(floor.floor_level);
			$('#title').append($title);          
		}
		

		$('select').change(function(){			
			//get the selected option 
			$selected = $('select option:selected');			
			
			var id = $selected.attr('value');
			
			//we need the get the floor plus attributes 
			var fl = findById(floors, id, 'floor_id');
			$('#afbeelding').attr('src', fl.floor_map);
			
			
		});

		$('#proceed').click(function(){
			$('form').submit();
		})

		
		$('select').trigger('change');

		

		function findById(arr, id, column){
			for(var i=0; i < arr.length; i++){
				if(arr[i][column] == id){
					return arr[i];
				}
			}
		}
	});

</script>

@endsection