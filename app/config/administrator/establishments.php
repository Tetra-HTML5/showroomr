<?php

/**
 * Establishments model config
 */

return array(

	'title' => 'Vestigingen',
	'single' => 'vestiging',
	'model' => 'Establishment',
	
	

	/**
	 * The display columns
	 */
	'columns' => array(
		'est_name' => array(
			'title' => 'Naam',
			),
		'est_address' => array(
			'title' => 'Adres',
			),
		'est_postal_code' => array(
			'title' => 'Postcode',
			),
		'city' => array(
			'title' => 'Stad',
			'relationship' => 'postalcode',
			'select' => "(:table).post_city"
			),
		'est_email' => array(
			'title' => 'E-mail',
			),
		'est_telephone' => array(
			'title' => 'Telefoon',
			),
		'est_opening_hours' => array(
			'title' => 'Openingsuren',
			),
		'est_picture' => array(
			'title' => 'Afbeelding',
			'type'   => 'Image',
            'output' => '<img src="'.url('(:value)').'" height="100">'
			),
		),

	/**
	 * The filter set
	 */
	'filters' => array(
		'est_name' => array(
			'title' => 'Naam',
			),
		'est_postal_code' => array(
			'title' => 'Postcode',
			),
		),
		
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'est_name' => array(
			'title' => 'Naam',
			'type' => 'text',
			),
		'est_address' => array(
			'title' => 'Adres',
			'type' => 'text',
			),
		'postalcode' => array(
			'title' => 'Stad',
			'type' => 'relationship',
			'name_field' => "post_city"
			),
		'est_email' => array(
			'title' => 'E-mail',
			'type' => 'text',
			),
        'est_telephone' => array(
            'title' => 'Telefoon',
            'type' => 'text',
        ),
		'est_opening_hours' => array(
			'title' => 'Openingsuren',
			'type' => 'text',
			),
		'est_picture' => array(
			'title' => 'Afbeelding',
			'type' => 'image',
            'location' => public_path() . '/',
			),
		),
	
	'permission' => function(){
		return Sentry::getUser()->hasAccess('establishment-management');
	},
);