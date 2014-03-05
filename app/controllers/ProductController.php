<?php

class ProductController extends BaseController {
	
	private $perPage = 3;
	
	/**
	 * Gets all the products
	 * @uses GET parameters 'search' and 'category_id'
	 * @return array|json products filtered on title and category
	 */
	public function getAllProducts(){
		// GET parameters
		$search = Input::get('search', false);
		$category_id = Input::get('category', false);

		// The establishment() filter ensures only products of the current establishment will be retrieved

		// Get paginated products
		if($category_id == 'promotions'){
			// If category equals promotions, only get the promotions
			$products = Product::establishment()->promotions()->where('prod_name', 'like', "%$search%")->alphabetical()->paginate($this->perPage);
		}
		elseif($category_id){
			// Get all products of the category and filter on the search argument
			$category = Category::find($category_id);
			$products = $category->products()->establishment()->where('prod_name', 'like', "%$search%")->alphabetical()->paginate($this->perPage);
		} 
		else{
			// Get all products which match with the search argument
			$products = Product::establishment()->where('prod_name', 'like', "%$search%")->alphabetical()->paginate($this->perPage);
		}

		return $products;
	}

	/**
	 * Product list page
	 * @return View product page
	 */
	public function showProducts(){
		$categories = Category::all();
		$products = $this->getAllProducts();		
		$search = Input::get('search');
		return View::make('frontend.products', array('products' => $products, 'search' => $search, 'categories' => $categories));
	}

	/**
	 * Product detail page
	 * @return View product detail page
	 */

	public function getProduct($id) {
		// Gets the product, throws 404 if the product could not be found
		$product = Product::find($id);
		if(!$product){
			App::abort(404, 'Page not found');
		}

		// Add 1 to view counter
		$product->prod_views++;
		$product->save();

		// Finds where the products are in stock (from pivot table)
		$availableFloorIds = Product_Establishment::where('product_id', '=', $id)->groupBy('ppe_floor')->lists('ppe_floor');
		$availableEstablishmentIds = Floor::whereIn('floor_id', $availableFloorIds)->groupBy('establishment_id')->lists('establishment_id');
		$availableEstablishments = Establishment::whereIn('est_id', $availableEstablishmentIds)->get();
		
		// Related products
		// Finds products in the same category and displays maximum 4 of them in random order
		$category = $product->categories->lists('cat_id');
			
		if(count($category) < 1){
			$relatedProducts = null;
		} else {
			$relatedProductIds = DB::table('category_product')
			->whereIn('category_id', $category)
			->where('product_id', '!=', $id)
			->distinct()
			->take(4)
			->orderBy(DB::raw('RAND()'))
			->lists('product_id');

			// If there are no products off the same category, we don't have to check for establishments
			if (count($relatedProductIds) == 0){
				$relatedProducts = null;
			} else {
				$relatedProducts = Product::establishment()->whereIn('prod_id', $relatedProductIds)->get();
			}
			
		}
		
		// Data array
		$data = array(
			'product' => $product, 
			'categories' => $product->categories,
			'availableEstablishments' => $availableEstablishments,
			'relatedProducts' => $relatedProducts
			);
		return View::make('frontend.productsDetails', $data);
	}

}