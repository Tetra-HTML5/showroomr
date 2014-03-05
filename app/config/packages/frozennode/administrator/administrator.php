<?php

return array(

	/**
	 * Package URI
	 *
	 * @type string
	 */
	'uri' => 'admin/entities',

	/**
	 * Page title
	 *
	 * @type string
	 */
	'title' => 'Showroomr Admin Panel',

	/**
	 * The path to the model config directory
	 *
	 * @type string
	 */
	'model_config_path' => app('path') . '/config/administrator',

	/**
	 * The path to the settings config directory
	 *
	 * @type string
	 */
	'settings_config_path' => app('path') . '/config/administrator/settings',

	/**
	 * The menu structure of the admin panel
	 *
	 * @type array
	 */
	'menu' => array(
		'products',
		'categories',
		'establishments',
		'floors',
		'customers',
		'faqs',
		'postalcodes',
		),
	/**
	 * The permission option is the highest-level authentication check that lets you define a closure that should return true if the current user
	 * is allowed to view the admin section. Any "falsey" response will send the user back to the 'login_path' defined below.
	 *
	 * @type closure
	 */
	'permission'=> function()
	{
		return AdministratorHelper::hasAccessToAdministrator();
	},

	/**
	 * The menu item that should be used as the default landing page of the administrative section
	 *
	 * @type string
	 */
	'home_page' => null,
	'dashboard_view' => 'admin.administratorDashboard',
	'use_dashboard' => true,
	

	/**
	 * The login path is the path where Administrator will send the user if they fail a permission check
	 *
	 * @type string
	 */
	'login_path' => 'admin/login',

	/**
	 * The logout path is the path where Administrator will send the user when they click the logout link
	 *
	 * @type string
	 */
	'logout_path' => 'admin/logout',

	/**
	 * This is the key of the return path that is sent with the redirection to your login_action.
     * Input::get('redirect') will hold the return URL.
	 *
	 * @type string
	 */
	'login_redirect_key' => 'redirect',

	/**
	 * Global default rows per page
	 *
	 * @type NULL|int
	 */
	'global_rows_per_page' => 20,
);
