<?php

class WishlistController extends BaseController {
		
	private $id;
	private $products;
	
	function __construct() {
       // Gets the ID from the customer
		$this->id = Session::get('customer.id');
		
		// Gets the wishlist
		$this->products = Customer::find($this->id)->products()->alphabetical()->get();
   	}
		
	/**
	 * Show the wishlist view
	 * @return View
	 */
	public function showWishlist(){
		return View::make('frontend.wishlist', array('products' => $this->products));	
	}	

	/**
	 * Toggles the wishlist state of a product
	 * @param int $prod_id ID of the product
	 * @return mixed state of product after the processing fo the function
	 */
	public function toggleProduct($prod_id){
		// Checks if the ID is in the wishlist
		$exists = in_array($prod_id, $this->products->lists('prod_id'));

		// If it exists, delete it, otherwise add it
		if($exists) {
			return $this->deleteProduct($prod_id);
		} else {
			return $this->addProductToList($prod_id);
		}
	}
	
	/**
	 *  Adds a product to the wishlist
	 *	@param int $prod_id ID of the product
	 *  @return mixed state of product after the processing fo the function
	 */
	public function addProductToList ($prod_id) {
		// Attaches the product to the currently logged in customer
		$customer = Customer::find($this->id);
		$customer->products()->attach($prod_id);
		return array('exists' => true);
	}

	/**
	 *  Deletes a product of the wishlist
	 *	@param int $prod_id ID of the product
	 *  @return mixed state of product after the processing fo the function
	 */	
	public function deleteProduct ($prod_id) {
		// Detaches the product of the currently logged in customer
		$customer = Customer::find($this->id);
		$customer->products()->detach($prod_id);
		return array('exists' => false);
	}


}