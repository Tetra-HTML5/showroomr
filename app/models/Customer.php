<?php

/**
 * Class Customer
 */
class Customer extends Eloquent {

    protected $table = 'customer';
	protected $primaryKey = 'cust_id';
	public $timestamps = false;
	
	public static $rules = array(
		'cust_email' => 'unique:customer|email');
	

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with products
     */
    public function products()
	{
		return $this->belongsToMany('Product', 'customer_product');
	}
}

?>          