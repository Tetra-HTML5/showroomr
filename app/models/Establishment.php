<?php

/**
 * Class Establishment
 */
class Establishment extends Eloquent
{
	protected $table = 'establishment';
	protected $primaryKey = 'est_id';
	public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with floors
     */
    public function floors()
	{
		return $this->belongsToMany('Floor');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Relationship with postalcodes
     */
    public function postalcode()
    {
        return $this->belongsTo('Postalcode','est_postal_code');
    }

    /**
     * Column rules
     */
    public static $rules = array(
        'est_name' => 'required|max:45', //VARCHAR(45), NOT NULL
        'est_address' => 'required|max:255', //VARCHAR(255), NOT NULL
        'est_postal_code' => 'required|max:10', //VARCHAR(10), NOT NULL
        'est_email' => 'email|max:45',
        'est_telephone' => 'max:45'
        
    );
	
}

?>