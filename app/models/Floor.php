<?php

/**
 * Class Floor
 */
class Floor extends Eloquent
{
	protected $table = 'floor';
	protected $primaryKey = 'floor_id';
	public $timestamps = false;
	
	public static $rules = array (
		'floor_map' => 'required', 
		'floor_level' => 'required|max:11');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Relationship with establishments
     */
    public function establishments()
	{
		return $this->belongsTo('Establishment','establishment_id');
	}

    /**
     * Accessor which detects if the floor_map field in the database contains an URL or only the file name
     * If it's the filename, return the URL to the assets/img/floors/ folder
     * @param string $value input
     * @return string $value output
     */
    public function getFloorMapAttribute($value){
        if(!starts_with($value, 'http')){
            return asset($value);
        } else {
            return $value;
        }
    }

    public function setFloorMapAttribute($value){
        $this->attributes["floor_map"] = 'assets/img/floors/' . $value;
    }
}

?>