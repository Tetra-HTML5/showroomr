<?php

/**
 * Products Per Establishment model config
 */

return array(

	'title' => 'Producten Per Vestiging',
	'single' => 'Product Per Vestiging',
	'model' => 'Product_Establishment',
 
	/**
	 * The display columns
	 */
	'columns' => array(
        'products' => array(
            'title' => 'Product',
            'relationship' => 'products',
            'select' => "(:table).prod_name"
        ),
        'establishments' => array(
            'title' => 'Vestiging',
            'relationship' => 'establishments',
            'select' => "(:table).est_name"
        ),
        'ppe_stock' => array(
            'title' => 'Voorraad'
        ),
        'ppe_xvalue' => array(
             'title' => 'X waarde'
        ),
        'ppe_yvalue' => array(
             'title' => 'Y waarde'
        ),
    ),

	/**
	 * The filter set
	 */
	'filters' => array(
        'product_id' => array(
            'title' => 'Product',
        ),
        'establishments' => array(
            'title' => 'Vestiging',
            'type' => 'relationship',
            'name_field' => 'est_name',
        ),
	),
		
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
        'products' => array(
            'title' => 'Product',
            'type' => 'relationship',
            'name_field' => 'prod_name',
        ),
        'establishments' => array(
            'title' => 'Vestiging',
            'type' => 'relationship',
            'name_field' => 'est_name',
        ),
		'ppe_stock' => array(
			'title' => 'Voorraad',
			'type' => 'number',
			),
        'ppe_xvalue' => array(
            'title' => 'X Waarde',
            'type' => 'number',
        ),
        'ppe_yvalue' => array(
            'title' => 'Y Waarde',
            'type' => 'number',
        ),
        'floors' => array(
            'title' => 'Verdieping',
            'type' => 'relationship',
            'name_field' => 'floor_level',
        ),
    ),

	'permission' => function(){
		return Sentry::getUser()->hasAccess('superuser');
	},
);