@extends('frontend.template')

@section('content')

<h1>Uw locatie bepalen</h1>
<h2 style="font-weight:normal;">Scan een nabije QR-code om uw locatie vast te stellen</h2>
<a href="{{ url('/route/scan/doScan') }}">
<button data-role="button" data-icon="flat-camera"  data-inline="true">
	Scan QR-code
</button> </a>

<div data-role="collapsible" data-collapsed-icon="carat-r" @if (count($errors->all()>0)) data-collapsed="false" @endif data-expanded-icon="carat-d">
	<h3 style="font-weight:normal;">Geen android toestel?</h3>
	@foreach ($errors->all() as $message)
		<span class="error">{{ $message }}</span><br/>
	@endforeach
	<p>
		Vul onderstaande velden in met de waarde op de QR-code.
	</p>
	<form method="post" action="{{ url('/route/customStartpoint') }}">
		<div class="ui-grid-a">
    		<div class="ui-block-a">
    			<div class="ui-bar">
    				X waarde:
    				<input type="text" name="x" id="x" />
    			</div>
    		</div>
    		<div class="ui-block-b">
    			<div class="ui-bar">
    				Y waarde:
    				<input type="text" name="y" id="y" />
    			</div>
    		</div>
		</div>
    	<div class="ui-bar">
    		Verdieping:
				<select name="floor"   data-native-menu="false">
			    	@foreach($floors as $floor)
			        	<option name="floor" id="floor" value="{{$floor->floor_id}}">{{$floor->floor_level}}</option>
			        	{{$floor->floor_id}}
			        @endforeach
				</select>
    	</div>			
			<button type="submit">
				Zoek route
			</button>
	</form>
</div>

@endsection