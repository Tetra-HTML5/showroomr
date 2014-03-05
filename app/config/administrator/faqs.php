<?php

/**
 * FAQs model config
 */

return array(

	'title' => 'FAQs',
	'single' => 'FAQ',
	'model' => 'FAQ',

	/**
	 * The display columns
	 */
	'columns' => array(
		'faq_question' => array(
			'title' => 'Vraag',
			),
		'faq_answer' => array(
			'title' => 'Antwoord'
			),
		'faq_visible' => array(
			'title' => 'Zichtbaar',
			'type' => 'enum',
    		'options' => array('0', '1'), //must be an array
			),
		),

	/**
	 * The filter set
	 */
	'filters' => array(
		'faq_question' => array(
			'title' => 'Vraag',
			),
		),
		
	/**
	 * The editable fields
	 */
	'edit_fields' => array(
		'faq_question' => array(
			'title' => 'Vraag',
			'type' => 'text',
			),
		'faq_answer' => array(
			'title' => 'Antwoord',
			'type' => 'text',
			),
		'faq_visible' => array(
			'title' => 'Zichtbaar',
			'type' => 'enum',
    		'options' => array('0', '1'), //must be an array
			),
		),

	'permission' => function(){
		return Sentry::getUser()->hasAccess('faq-management');
	}
	
	
);