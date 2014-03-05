<?php 
class AuthController extends BaseController {

	/**
	* Shows the login page
	* 
	* @return View
	**/
	public function getLogin()
	{
		return View::make('frontend.login');
	}

	/**
	 * Creates or logs in the customer based on his e-mail address.
	 *
	 * @return View
	 */
	public function postLogin()
	{
		$email = Input::get('email');

		// Checks if the email field contains a valid email address
		$validation = Validator::make(array('email' => $email), array('email' => 'required|max:45|email'));
		if($validation->fails()){
			// Return with input and errors
			return Redirect::back()->withInput()->withErrors($validation->errors());
		}

		// Finds customer with the given email address
		$user = Customer::where('cust_email', '=', $email)->first();

		// Create new customer if none found
		if(!$user){
			$user = new Customer;
			$user->cust_email = $email;
			
			if (!$user->save()) {
            	return Redirect::to('/login')->withErrors($user->errors());
        	}
		}
		$id = $user->cust_id;

		// Creates session
		Session::put('customer.loggedin', true);
		Session::put('customer.id', $id);
		Session::put('customer.email', $email);

		return Redirect::to('/');
	}
	
	/**
	* Logs out a user
	* 
	* @return View
	**/
	public function logout() {		
		Session::flush();		
		return Redirect::to('/login');
	}

}
