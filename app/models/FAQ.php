<?php

/**
 * Class FAQ
 */
class FAQ extends Eloquent
{
	protected $table = 'FAQ';
	protected $primaryKey = 'faq_id';
	public $timestamps = false;


 public static $rules = array(
        'faq_question' => 'required',
	    'faq_answer' => 'required',
		'faq_visible' => 'min:0|max:1|required|numeric');
}
?>
