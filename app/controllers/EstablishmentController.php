<?php 
class EstablishmentController extends BaseController {

	/**
	* Shows the establishment page
	* 
	* @return View
	**/
	public function getEstablishments()
	{
		$establishments = Establishment::with('postalcode')->get();
		return View::make('frontend.establishment', array('establishments'=> $establishments));
	}

	/**
	* Stores establishment ID in session and redirects to the previous page
	*
	* @return Redirect
	**/
	public function postEstablishments()
	{
		$estId = Input::get('establishment');
		Session::put('establishment', $estId);
		return Redirect::to('/');
	}	

}
