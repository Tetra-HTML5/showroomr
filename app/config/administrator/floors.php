<?php

/**
 * Floors model config
 */

return array(

    'title' => 'Verdiepingen',
    'single' => 'verdieping',
    'model' => 'Floor',


    /**
     * The display columns
     */
    'columns' => array(
        'floor_level' => array(
            'title' => 'Verdieping',
        ),
        'floor_map' => array(
            'title' => 'Map',
            'type'   => 'Image',
            'output' => '<img src="(:value)" height="100">'
        ),
        'establishment_id' => array(
            'title' => 'Vestiging',
            'relationship' => 'establishments',
            'select' => "(:table).est_name"
        ),
    ),

    /**
     * The filter set
     */
    'filters' => array(
        'floor_level' => array(
            'title' => 'Verdieping',
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
        'floor_level' => array(
            'title' => 'Verdieping',
            'type' => 'text',
        ),
        'floor_map' => array(
            'title' => 'Map',
            'type' => 'file',
            'location' => public_path() . '/assets/img/floors/',
            'mimes' => 'jpg,jpeg,png'
        ),
    ),

    'permission' => function(){
            return Sentry::getUser()->hasAccess('floor-management');
        },
);