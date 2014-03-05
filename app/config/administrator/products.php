<?php

/**
 * Products model config
 */
 

return array(

	'title' => 'Producten',
	'single' => 'product',
	'model' => 'Product',


	/**
	 * The display columns
	 */
	'columns' => array(
		'prod_name' => array(
			'title' => 'Naam',
			),
		'prod_price' => array(
			'title' => 'Prijs',
			),
		'prod_description' => array(
			'title' => 'Beschrijving',
			),
		'prod_picture' => array(
			'title' => 'Afbeelding',
			'type'   => 'Image',
            'output' => '<img src="'. url('/assets/img/products/(:value)') .'" height="100">'
			),
        'prod_views' => array(
            'title' => 'Aantal keer bekeken',
        ),
        'prod_promotion' => array(
            'title' => 'Promotie',
        ),
	),

	/**
	 * The filter set
	 */
	'filters' => array(
		'prod_name' => array(
			'title' => 'Naam',
			),
		'categories' => array(
			'title' => 'Categorie',
			'type' => 'relationship',
			'name_field' => 'cat_description',
			),

		),
		
	/**
	 * The editable fields
	 */
	'edit_fields' => array(

		'prod_name' => array(
			'title' => 'Naam',
			'type' => 'text',
			),
		'prod_price' => array(
			'title' => 'Prijs',
			'type' => 'text',
			),
		'prod_description' => array(
			'title' => 'Beschrijving',
			'type' => 'text',
			),
		'prod_picture' => array(
			'title' => 'Afbeelding',
			'type' => 'image',
            'value' => 'defaultproduct.jpg',
            'location' => public_path() . '/assets/img/products/',
			),
        'prod_promotion' => array(
            'title' => 'Promotie',
            'type' => 'text',
        ),
		'categories' => array(
			'title' => 'Categorieen',
			'type' => 'relationship',
			'name_field' => 'cat_description',
         	),
		),

		'permission'=> function()
		{
			$user = Sentry::getUser();
			return $user->hasAccess('product-management');
		},
);