<?php

/**
 * Class Product_Establishment from pivot table Product_Establishment
 */
class Product_Establishment extends Eloquent
{
	protected $table = 'product_establishment';
	protected $primaryKey = 'id';
	public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Relationship with products
     */
    public function products()
    {
        return $this->belongsTo('Product','product_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Relationship with establishments
     */
    public function establishments()
    {
        return $this->belongsTo('Establishment','establishment_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with floors
     */
    public function floors()
    {
        return $this->belongsToMany('Floor','product_establishment','id','ppe_floor')->withPivot('ppe_floor');
    }
}
?>