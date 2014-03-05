<?php

/**
 * Customers model config
 */

return array(

	'title' => 'Klanten',
	'single' => 'klant',
	'model' => 'Customer',
	
	
	/**
	 * The display columns
	 */
	'columns' => array(
		'cust_email' => array(
			'title' => 'E-mail',
			),
		),

	/**
	 * The filter set
	 */
	'filters' => array(
		'cust_email' => array(
			'title' => 'E-mail',
			),
		),
		
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'cust_email' => array(
			'title' => 'E-mail',
			'type' => 'text',
			),
		),

	'permission' => function(){
		return Sentry::getUser()->hasAccess('customer-management');
	},
);