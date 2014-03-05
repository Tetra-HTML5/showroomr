<?php

class HomeController extends BaseController {

	/**
	 * Shows the homepage with establishment info, most viewed products and promotions
	 * return View
	 */
	public function showHomepage()
	{
		$data = array(
			'establishment' => Establishment::with('postalcode')->find(Session::get('establishment')),
			'mostViewedProducts' =>  Product::establishment()->mostViewed()->take(3)->get(),
			'promotions' => Product::establishment()->promotions()->take(3)->get(),
			);
		return View::make('frontend.home', $data);
	}

}