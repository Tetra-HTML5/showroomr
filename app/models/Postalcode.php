<?php

/**
 * Class Postalcode
 */
class Postalcode extends Eloquent
{
	protected $table = 'postalcode';
	protected $primaryKey = 'post_code';
	public $timestamps = false;
	
	public static $rules = array (
		'post_code' => 'required|max:10', 
		'post_city' => 'required|max:45');
	

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * Relationship with establishments
     */
    public function establishments()
    {
        return $this->hasMany('Establishment','est_postal_code');
    }
	
}

?>