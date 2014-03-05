$(document).ready(function(){
	// Master template contains a 'baseurl' tag where the base URL is defined.
	// This is used to ensure that Ajax requests have the right URL
	var baseurl = $('baseurl').html();

	/* Toggle wishlist state of product */
	$('.products').on('click', '.wishlist', function(){
		var id = $(this).attr('data-id');
		$buttons = $('.wishlist[data-id='+id+']');
		$.ajax({
			url : baseurl+'/wishlist/toggle/' + id,
			dataType : 'json'
		}).done(function(data){
			if(data.exists){
				$buttons.buttonMarkup({ theme: "e" });
				// Yellow
			} else {
				$buttons.buttonMarkup({ theme: "d" });
				// Red
			}
		});
		return false;
	});

	/* Redirect to position page */	
	$('.products').on('click', '.map', function(){
		location.href = baseurl+"/route/product/" + $(this).attr('data-id');
		return false;
	});

	/* Wishlist page: delete products from wishlist */
	$('.products').on('click', '.wishlist-delete', function(){
		$this = $(this);
		var id = $(this).attr('data-id');
		$.ajax({
			url : baseurl+'/wishlist/delete/' + id,
			dataType : 'json'
		}).done(function(data){
			$this.closest('li').slideUp(400, function(){$this.remove()});

			// If only heading is visible, refresh page
			var size = $("#wishlist li").size();
			if(size <= 1){
				location.reload(); 
			}
		});
		return false;
	});

});