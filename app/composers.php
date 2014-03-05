<?php
use Frozennode\Administrator\Menu;

class AdministratorMenuComposer {

	public function compose($view)
	{
		if(AdministratorHelper::hasAccessToAdministrator()){
			// Fetch menu from Administrator package
			$menu = App::make('admin_menu')->getMenu();
			$menu = array_except($menu, array('Settings'));
			$settingsPrefix = App::make('admin_config_factory')->getSettingsPrefix();
			$pagePrefix = App::make('admin_config_factory')->getPagePrefix();
			$configType = App::bound('itemconfig') ? App::make('itemconfig')->getType() : false;

			// If the user has 'product-management' access, access to the grid positioning and product positioning will be allowed
			$positioningAllowed = Sentry::getUser()->hasAccess('product-management');

			// Pass the data to the navigation partial
			$data = array(
				'menu' => $menu, 
				'settingsPrefix' => $settingsPrefix,
				'pagePrefix' => $pagePrefix, 
				'configType' =>$configType, 
				'user' => Sentry::getUser(),
				'positioningAllowed' => $positioningAllowed
			);

			// Nest Administrator menu
			$view->nest('navPages', 'admin.administratorMenu', $data);
		}
		
		// Name of admin panel
		$view->with('siteName', 'Showroomr Admin Panel');
	}


}

?>