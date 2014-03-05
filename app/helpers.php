<?php

Class AdministratorHelper {

	/**
	 * Checks if an user belongs to any of the given groups
	 *
	 * @param $user User Sentry user
	 * @param $groups array Array with the permitted group
	 * @return boolean returns if the user is in any of the given groups
	 */
	public static function inAnyGroup($user, $groups){
		// Loop through the array
		foreach($groups as $key => $value){
			// Try to load the group and check if the user is in this group
			try {
				$group = Sentry::findGroupByName($value);
				if($user->inGroup($group)){
					// User belongs in one of the groups
					return true;
				}
			} catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e){
				// If the group is not found, don't show an exception but log this message
			    Log::error($e->getMessage());
			}
		}
		// User belongs to none of the groups
		return false;
	}

	/**
	 * Returns if the currently logged in user has access to the Administrator package
	 *
	 * @return boolean returns if the user has access or not
	 */
	public static function hasAccessToAdministrator(){
		$allowed = array('Administrator', 'Medewerker', 'Chatmedewerker');
		return Sentry::check() && self::inAnyGroup(Sentry::getUser(), $allowed);
	}
}