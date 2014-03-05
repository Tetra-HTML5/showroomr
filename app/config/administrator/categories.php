<?php

/**
 * Categories model config
 */

return array(

	'title' => 'Categorieen',
	'single' => 'categorie',
	'model' => 'Category',

	/**
	 * The display columns
	 */
	'columns' => array(
		'cat_description' => array(
			'title' => 'Beschrijving',
			),
		),

	/**
	 * The filter set
	 */
	'filters' => array(
		'cat_description' => array(
			'title' => 'Beschrijving',
			),
		),

	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'cat_description' => array(
			'title' => 'Beschrijving',
			'type' => 'text',
			),
		),

	'permission' => function(){
		return Sentry::getUser()->hasAccess('category-management');
	},
);