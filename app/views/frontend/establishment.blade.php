@extends('frontend.template')

@section('content')

        <div class="article">
        	<h1 style="margin-top:0;">Kies uw vestiging</h1>
            <div id="establishment-image"></div>
           	<div data-role="fieldcontain" id="select">
           		{{ Form::open() }}
            	<select name="establishment" id="select-choice-a"  data-native-menu="false">
            		@foreach($establishments as $establishment)
            			<option value="{{$establishment->est_id}}" data-image="{{$establishment->est_picture}}">{{$establishment->est_name}}</option>
            		@endforeach
		        </select>	
		        {{ Form::close() }}
			</div>

            <ul data-role="listview" data-inset="true" style="margin-top:0;">
	        <li data-role="list-divider">
	        	<div class="ui-icon-flat-location" style="display:inline; margin-right:5px;"></div>Adres
	        </li>
	        <li class="data-collapse" id="address"></li>
			<li data-role="list-divider">
	        	<div class="ui-icon-flat-mail" style="display:inline; margin-right:5px;"></div>Contact
	        </li>
	        <li class="data-collapse" id="contact"></li>
	        <li data-role="list-divider">
	        	<div class="ui-icon-flat-time" style="display:inline; margin-right:5px;"></div>Openingsuren
	        </li>
	        <li class="data-collapse"  id="hours"></li>
	      	</ul>			
        </div>

<script type="text/javascript">
	$(document).ready(function(){
		var establishments = {{$establishments->toJson()}};

		$('select').change(function(){
			setPicture();
			$selected = $('select option:selected');
			var id = $selected.attr('value');
			var est = findById(establishments, id, 'est_id');
				$('#address').html(est.est_address + "<br/>" + est.est_postal_code + " " + est.postalcode.post_city);
				$('#contact').html(est.est_email + "<br/>" + est.est_telephone);
				$('#hours').html(est.est_opening_hours);
		});

		$('#proceed').click(function(){
			$('form').submit();
		})

		
		$('select').trigger('change');

		function setPicture(){
			$selected = $('select option:selected');
			$('#establishment-image').css('background', 'url('+$selected.attr('data-image')+')');
			
		}

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

@section('footer')

<div data-role="footer" data-position="fixed">
<button id="proceed" data-icon="flat-checkmark" type="submit">Selecteer deze vestiging</button>

</div>
@endsection