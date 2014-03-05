<?php

/**
 * Class Category
 */
class Category extends Eloquent
{
	protected $table = 'category';
	protected $primaryKey = 'cat_id';
	public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with products
     */
    public function products()
	{
		return $this->belongsToMany('Product','category_product');
	}

    /**
     * Column rules
     */
    public static $rules = array(
        'cat_description' => 'required|max:255|unique:category',
    );
	
}

?>