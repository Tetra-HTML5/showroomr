<?php

/**
 * RouteController
 *
 * Application flow:
 *
 * /product/{productId}		setProductEndPoint($productId)		Sets previousUrl and productId
 * /scan 					showQrPage							Shows the QR code scan page
 * /{x}/{y}/{floor} 		setStartPoint($x,$y,$floor)			Set start point (red. from Android app)
 * /show 					showRoute							Shows the route!
 */
Class RouteController extends BaseController {

	/**
	 * Sets the ID of product in session and redirects to Scan QR page
	 * /product/{productId}
	 * @param int $productId  ID of the product
	 * @return Redirect
	 */
	public function setProductEndPoint($productId){
		// New end point, we'll clear the session:
		Session::forget('position');

		// Put the previous URL in the session (if there's no route on the same floor, the user can go back to the page where he came from)
		Session::put('position.previousUrl', URL::previous());

		// Set ID of product in session and redirect to scan QR page so the user can scan the nearest QR code
		Session::put('position.product', array('id' => $productId));
		return Redirect::to('route/scan');
	}

	/**
	 * Page where user can scan the nearest QR (needed for route calculations between the user and a product)
	 * This page is connected to the Android application. After scanning the QR code, the setStartPoint($x, $y, $floor) function will be
	 * used to set the start point of the route.
	 * /scan
	 * @uses setStartPoint($x, $y, $floor)
	 * @return View QR code scan page
	 */

	public function showQrPage(){
		$id = Session::get('establishment');
		$floors = Floor::where('establishment_id', '=', $id)->get();
		return View::make('frontend.scanQr', array('floors' => $floors));				
	}

	/**
	 * Set start Point and redirect to the show route page (redirected from Android app)
	 * /{x}/{y}/{floor}
	 * @param int $x 
	 * @param int $y 
	 * @param string $floor 
	 * @return Redirect
	 */
	public function setStartPoint($x, $y, $floor){
		Session::put('position.startPoint', array('x' => $x, 'y' => $y, 'floor' => $floor));
		return Redirect::to('route/show');
	}
	

	/**
	 * Function to get the manually inputted values of the start location
	 * @return Redirect
	 */
	public function postStartPoint()
	{
		$x = Input::get('x');
		$y =  Input::get('y');
		$floor =  Input::get('floor');

		$validator = Validator::make(
			array(
				'x' => $x,
				'y' => $y
			), array(
				'x' => 'required|integer|min:0',
				'y' => 'required|integer|min:0'
		));

		if($validator->fails()){
			return Redirect::to('route/scan')->withErrors($validator);
		}

		return $this->setStartPoint($x, $y, $floor);
	}	
	
	/**
	 * Show route page.
	 * Gets all the possible end points for the product
	 * /show
	 */
	public function showRoute(){
		// Check if start point and end point (product) are set
		if(Session::get('position.product') == null || Session::get('position.startPoint') == null){
			throw new Exception('Start point or end product not set, cannot calculate route');
		}

		// Get product ID, establishment ID and start point from session
		$productId = Session::get('position.product.id');
		$establishmentId = Session::get('establishment');
		$startPoint = Session::get('position.startPoint');

		// Floor
		$currentFloorId = Session::get('position.startPoint.floor');
		$floor = Floor::find($currentFloorId);

		// Product
		$product = Product::find($productId);
		
		// Gets the Product_Establishment entries of the product in the current establishment
		$floorsInEstablishment = Floor::where('establishment_id', '=', $establishmentId)->lists('floor_id');
		$prod_est = Product_Establishment::where('product_id', '=', $productId)->whereIn('ppe_floor', $floorsInEstablishment)->get();

        // Finds if the product exists on other floors in the same establishment
        $otherFloors = DB::table('product_establishment')
            ->join('floor', 'product_establishment.ppe_floor', '=', 'floor.floor_id')
            ->where('product_id', '=', $productId)
            ->where('floor.establishment_id', '=', $establishmentId)
            ->where('floor.floor_id', '!=', $currentFloorId)
            ->groupBy('floor.floor_id')
            ->lists('floor.floor_level');

		// Puts all the possible end points (in the current establishment) for the product in a JSON encoded array
		$possibilities = array();
		foreach($prod_est as $pivot){
			$possibilities[] = array($pivot->ppe_xvalue, $pivot->ppe_yvalue, $pivot->ppe_floor);
		}
		$possibilities = json_encode($possibilities);

		// Return the view with the needed data
		$data = array(
			'startPoint' => $startPoint, 
			'otherFloors' => $otherFloors, 
			'floor' => $floor,
			'possibilities' => $possibilities, 
			'product' => $product,
			'previousUrl' => Session::get('position.previousUrl', 'products')
		);
		return View::make('frontend.pathfinding', $data);

	}

}

?>