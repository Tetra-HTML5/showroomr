<?php

/**
 * Postalcodes model config
 */

return array(

	'title' => 'Postcodes',
	'single' => 'postcode',
	'model' => 'Postalcode',

	/**
	 * The display columns
	 */
	'columns' => array(
		'post_code' => array(
			'title' => 'Postcode',
			),
		'post_city' => array(
			'title' => 'Stad',
			),
		),

	/**
	 * The filter set
	 */
	'filters' => array(
		'post_code' => array(
			'title' => 'Postcode',
			),
		'post_city' => array(
			'title' => 'Stad',
			),
		),
		
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'post_code' => array(
			'title' => 'Postcode',
			'type'=>'text',
			),
		'post_city' => array(
			'title' => 'Stad',
			'type'=>'text',
			),
		),

	'permission' => function(){
		return Sentry::getUser()->hasAccess('postalcode-management');
	},
);