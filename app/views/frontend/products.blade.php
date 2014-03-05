@extends('frontend.template')

@section('content')
<div id="wrapper">
	<form method="get">
	<input type="search" name="search" value="{{{ Input::get('search') }}}" placeholder="Zoeken..." />
	<div class="ui-grid-a" style="margin-top:-10px;">
		<div class="ui-block-a">
			<select name="category" id="select-choice-a" data-native-menu="false">
				<option value="0">
					Alle categorieën
				</option>
				<option value="promotions">
					Promoties
				</option>
				@foreach($categories as $category)
					<option value="{{$category->cat_id}}" data-image="{{$category->cat_id}}">{{$category->cat_description}}</option>
				@endforeach
			</select>
		</div>
		<div class="ui-block-b">
			<input type="submit" data-icon="search" value="Zoek" />
		</div>
	</div>
</form>
<ul data-role="listview" data-inset="true" id="products" class="products">
	<li data-role="list-divider">
		@if ($search==null)
			Producten
		@else
			Resultaten voor {{ $search }}
		@endif
	</li>

	@foreach ($products as $product)
		@include('frontend.product', array('product' => $product))
	@endforeach
	@if (count($products) == 0)
		<li>
			Geen producten gevonden
		</li>
	@endif
</ul>

<div class="paginate" style="display:none;">
<?php echo $products->links(); ?>
</div>
</div>
@endsection

@section('head')
<script type="text/javascript" src="{{ asset('assets/js/jquery.infinitescroll.js')}}"></script>
<script type="text/javascript">
$(document).ready(function(){	
	
	var category = '{{ Input::get('category') }}';
	if( category != '') {
		$("select[name=category] option[value="+category+"]").attr('selected', 'selected');
	}
	$('select[name=category]').selectmenu('refresh');

	//when a new category is selected, the page loads again
	//this way a user doesn't have to click on 'search' 
	$('select').change( function() {
    	 $('form').submit()
    });


	//paginate the products
	$('.paginate a').attr('data-ajax', 'false');
	$('#products').infinitescroll({
		navSelector     : ".pagination",
		nextSelector    : ".pagination a:last",
		itemSelector    : "#products li",
		debug           : true,
		dataType        : 'json',
		extraScrollPx: 150,
		appendCallback  : false,
		loading: {
			msg: null,
			img: "{{url('assets/img/spacer.png')}}",
			msgText: "<div style='text-align:center; display:block; width:100%;'><img src='{{ url('assets/img/loading.GIF') }}'/><br/>Laden...</div>",
			finished: undefined,
            finishedMsg: "<div style='text-align:center;'>Geen producten meer</div>",
		},
		path: function(index) {
			return "products/a?page=" + index + "&search={{Input::get('search')}}&category={{Input::get('category')}}";
		},
		
	},function(json, opts) {
		products = json.data;
		for(var i = 0; i < products.length; i++){
			var product = products[i];
			var html = '<li data-theme="c" data-content-theme="d">';
			
			//checking if the product is in the wishlist
			var theme = null;
			if(product.inWishlist){
				theme = "e";
			} else {
				theme = "d";
			}
			var promotion = false;
			if(product.prod_promotion != 0){
				promotion = true;
			}

			// Adding the list item to the list
			html+= '<a href="{{ url("products") }}/' + product.prod_id + '">';
			html+= '<img src="{{ url("assets/img/products") }}/'+ product.prod_picture +'" alt="'+ product.prod_name +'"';
			html+= '<h3>' + product.prod_name;
			if(promotion){
				html+='&nbsp;-&nbsp;' + (product.prod_promotion*100) + '%';
			}
			html+= '</h3><br/>';
			if(promotion){
				html+='<p style="text-decoration: line-through; display:inline;">&euro; '+product.prod_price+'</p>&nbsp;<p style="color: red; display:inline;">&euro; '+ product.actualPrice +'</p><br/>';
			} else {
				html+='<p>&euro;&nbsp;'+ parseFloat(product.actualPrice).toFixed(2) +'</p>';
			}
			html+= '<button data-role="button" data-theme="'+theme+'" data-ajax="false" data-icon="star" data-iconpos="notext" data-inline="true" data-id="'+product.prod_id+'" class="wishlist">Favoriet</button>';
			html+= '<button data-role="button" data-icon="location" data-iconpos="notext" data-inline="true" data-id="'+product.prod_id+'" class="map">Kaart</button></a>';
			html+= '</li>';
			$('#products').append(html).trigger('create');
		}
		$('#products').listview('refresh');
	});

});
</script>
<script type="text/javascript" src="{{ asset('assets/js/showroomr.product.js')}}"></script>
@endsection