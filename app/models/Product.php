<?php

/**
 * Class Product
 */
class Product extends Eloquent
{
	protected $table = 'product';
	protected $primaryKey = 'prod_id';
	protected $appends = array('actualPrice', 'inWishlist');
	public $timestamps = false;
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with categories
     */
    public function categories()
	{
		return $this->belongsToMany('Category');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with establishments
     */
    public function establishments()
	{
		return $this->belongsToMany('Establishment','product_establishment')->withPivot('ppe_stock', 'ppe_xvalue', 'ppe_yvalue');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with customers, used for wishlists
     */
    public function customers()
	{
		return $this->belongsToMany('Customer','customer_product');
	}

    /**
     * Column rules
     */
    public static $rules = array(
        'prod_name' => 'required|max:45', //VARCHAR(45), NOT NULL
        'prod_price' => 'required|max:1000000000|numeric|min:0',
        'prod_picture' => 'max:255', //VARCHAR(255)
        'prod_description' => 'required',  //NOT NULL (LONGTEXT dus geen max nodig)
        'prod_promotion' => 'numeric|min:0|max:1'
    );

    /**
     * @param $query
     * @return Scope for getting promoted products
     */
    public function scopePromotions($query){
    	return $query->where('prod_promotion', '!=', 0)->orderBy('prod_promotion', 'desc');
    }

    /**
     * @param $query
     * @return Scope for most viewed products
     */
    public function scopeMostViewed($query){
    	return $query->orderBy('prod_views', 'DESC');
    }

    /**
     * @param $query
     * @return Scope to get the products in the selected establishment of the user
     */
    public function scopeEstablishment($query){
        // All the floors in the current establishment
        $floors = Floor::where('establishment_id', '=', Session::get('establishment'))->lists('floor_id');

        // If an establishment has no floors, we will use ID '-1',
        // this means none of the product will be returned
        if(empty($floors)){
            $floors = array(-1);
        }

        // Join with product_establishment table and select all entries which match with these floors
        // Group by product_id to prevent returning duplicate products
        return $query->join('product_establishment', function($join){
                $join->on('product_establishment.product_id', '=', 'product.prod_id');
        })->whereIn('product_establishment.ppe_floor', $floors)->groupBy('product_establishment.product_id');
    }

    /**
     * @param $query
     * @return Scope for getting products alphabetically ordered by name
     */
    public function scopeAlphabetical($query){
        return $query->orderBy('prod_name', 'asc');
    }

    /**
     * @return bool
     * Checks if the product exists in the wishlist of the logged in customer
     */
    public function getInWishlistAttribute(){
        if(Session::get('customer.id')){
            $wishlist = Customer::find(Session::get('customer.id'))->products->lists('prod_id');
            return in_array($this->prod_id, $wishlist);
        }
        else {
            return false;
        }
    }

    /**
     * @return double
     * Gets the actual price (reduction included)
     */
    public function getActualPriceAttribute(){
        return round($this->prod_price * (1-$this->prod_promotion), 2);
    }
}
?>