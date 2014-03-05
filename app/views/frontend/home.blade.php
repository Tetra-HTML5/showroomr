@extends('frontend.template')

@section('content')

        <div class="article">
        		<h1>{{ $establishment->est_name }}</h1>
				<a data-ajax="false" data-role="button" href="{{ url('/establishment') }}" data-icon="gear">Vestiging wijzigen</a>
			<ul data-role="listview" data-inset="true" style="margin-top:0; clear:left;">
	        <li data-role="list-divider">
	        	<div class="ui-icon-flat-location" style="display:inline; margin-right:5px;"></div>Adres
	        </li>
	        <li class="data-collapse" id="address">
	        	{{ $establishment->est_address }}<br/>
	        	{{ $establishment->postalcode->post_code }} {{ $establishment->postalcode->post_city }}
	        </li>
			<li data-role="list-divider">
	        	<div class="ui-icon-flat-mail" style="display:inline; margin-right:5px;"></div>Contact
	        </li>
	        <li class="data-collapse" id="contact">
	        	{{ $establishment->est_email }}<br/>
	        	{{ $establishment->est_telephone }}
	        </li>
	        <li data-role="list-divider">
	        	<div class="ui-icon-flat-time" style="display:inline; margin-right:5px;"></div>Openingsuren
	        </li>
	        <li class="data-collapse" id="hours">
	        	{{ $establishment->est_opening_hours }}
	        </li>
	    </ul></div><!-- /article -->
	    <div data-role="tabs" id="tabs" class="products">
	    	<div data-role="navbar" data-iconpos="top" style="margin-bottom:-50px;">
	    		    <ul>
		    			<li><a href="#promotions" data-icon="tag" class="ui-btn-active">Promoties</a></li>
		    			<li><a href="#most-viewed" data-icon="eye">Meest bekeken</a></li>
	    		    </ul>
	    	</div>
	    	<div id="promotions">  

	    		@if(count($promotions) >= 1) <!-- if there are no promotion don't show else do show.-->
	    		<ul data-role="listview" data-inset="true" >
	    			@foreach ($promotions as $product) 
	    				@include('frontend.product', array('product' => $product))
	    			@endforeach
	    		</ul>
	    		@else
				<ul data-role="listview" data-inset="true" >
	    			<li>Geen promoties gevonden</li>
	    		</ul>
	    		@endif
	    		@if(count($promotions) == 3)
	    		<a href="{{ url('products?search=&category=promotions') }}" data-role="button">Bekijk alle promoties</a>
	    		@endif
	    	  </div>
	    	  <div id="most-viewed">
	    		<ul data-role="listview" data-inset="true" >
	    			@if(count($mostViewedProducts) >= 1) <!-- if there are no promotion don't show else do show.-->
		    			@foreach ($mostViewedProducts as $product)
		    				@include('frontend.product', array('product' => $product))
		    			@endforeach
		    		@else
						<ul data-role="listview" data-inset="true" >
			    			<li>Geen meest bekeken producten gevonden</li>
			    		</ul>
		    		@endif
	    		</ul>
	    	  </div>
	    </div>
       
@endsection

@section('head')
<script type="text/javascript" src="{{ asset('assets/js/showroomr.product.js')}}"></script>
@endsection