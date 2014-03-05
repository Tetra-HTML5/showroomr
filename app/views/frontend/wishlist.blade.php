@extends('frontend.template')

@section('content')

@if (count($products) == 0)
	<div class="exclamation">
		!
	</div>
	<div class="exclamation_text">
		<p> Je hebt nog geen producten in je verlanglijstje</p>
		<a data-ajax="false" data-role="button" href="{{ url('/products') }}" data-icon="plus" data-inline="true">Producten toevoegen</a>
	</div>
@else
<ul data-role="listview" data-inset="true" class="products">
	<li data-role="list-divider">Jouw favoriete producten</li>
	@foreach ($products as $product)
		@include('frontend.product', array('product' => $product, 'wishlist' => true))
	@endforeach
</ul>
@endif
@endsection

@section('head')
<script type="text/javascript" src="{{ asset('assets/js/showroomr.product.js')}}"></script>
@endsection