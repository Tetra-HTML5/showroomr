@extends('frontend.template')
@section('content')

	<h1>{{ $product->prod_name }}</h1>
	<div style="margin-left:-20px; padding-left:20px; padding-right:40px; background:url({{url('assets/img/products') . '/'. $product->prod_picture}}); background-size:cover; background-position:center; width:100%; height:200px; display:block;" class="products">
		<?php $theme = $product->inWishlist ? "e" : "d"; ?>
		<button data-icon="star" data-inline="true" class="wishlist" data-theme="{{ $theme }}" data-id="{{ $product->prod_id }}">Verlanglijst</button>
        <button data-icon="location" data-inline="true" onclick="location.href='{{ url('route/product') }}/'+ {{ $product->prod_id }}">Toon route</button>
	</div>

	<div>
	<p>
		<span id="price">
			&euro; {{ number_format((($product->prod_price)-($product->prod_price)*($product->prod_promotion)), 2, ',',' ') }}
		</span>
		{{ $product -> prod_description }}
	</p>
	<p id="categories">
		@foreach ($categories as $id => $category)
        	<span>{{ $category -> cat_description }}</span>
        @endforeach      
	</p>
	<p>
		<strong>Beschikbaarheid:</strong><br/>
		@if(count($availableEstablishments) > 0)
			@foreach ($availableEstablishments as $establishment) 
				{{ $establishment -> est_name }} </br></br>
			@endforeach
		@else
				Dit product is momenteel niet beschikbaar
		@endif
	</p>
</div>
	@if (count($relatedProducts) == 0)
		<div></div>
	@else
		<div>
        	<h3>Vergelijkbare producten</h2>
        </div>
        <ul data-role="listview" data-inset="true" class="products">
        	@foreach ($relatedProducts as $relProduct)
				@include('frontend.product', array('product' => $relProduct))
        	@endforeach
        </ul>
    @endif
@endsection

@section('head')
	<script type="text/javascript" src="{{ asset('assets/js/showroomr.product.js')}}"></script>
@endsection