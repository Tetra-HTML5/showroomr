<?php $theme = $product->inWishlist ? "e" : "d"; ?>
<?php $promotion = $product->prod_promotion != 0 ? true : false; ?>
<?php $wishlist = isset($wishlist) ? true : false; ?>
<li data-theme="c" data-content-theme="d">
	<a href="{{ url("products") }}/{{ $product->prod_id }}">
		<img src="{{ url('/assets/img/products') . '/' . $product->prod_picture }}" alt="{{ $product->prod_name }}"/>

		<h3>
			{{ $product->prod_name }}
			@if ($promotion)
				&nbsp;-&nbsp;{{$product->prod_promotion*100}}%
			@endif
		</h3>
		@if ($promotion)
			<p style="text-decoration: line-through; display:inline;">&euro; {{ number_format($product->prod_price, 2) }}</p>
			<p style="color: red; display:inline;">&euro; {{ number_format($product->actualPrice, 2) }}</p><br/>
		@else
			<p>&euro; {{ number_format($product->actualPrice, 2) }}</p>
		@endif
		{{-- Only show delete button if the user is on the wishlist page --}}
		@if ($wishlist)
		<button data-role="button" data-icon="delete" data-ajax="false" data-iconpos="notext" data-inline="true" data-id="{{ $product->prod_id }}" class="wishlist-delete">Verwijder</button>
		@else
		<button data-role="button" data-theme="{{ $theme }}"  data-icon="star" data-iconpos="notext" data-inline="true" data-id="{{ $product->prod_id }}" class="wishlist">Favoriet</button>
		@endif
		<button data-role="button" data-icon="location" data-iconpos="notext" data-inline="true" data-id="{{ $product->prod_id }}" class="map">Kaart</button>
	</a>
</li>