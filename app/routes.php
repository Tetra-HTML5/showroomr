<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
** Login routes and establishement routes (they don't have filters attached)
*/
Route::post('/login', array('uses'=>'AuthController@postLogin'));
Route::get('/login', array('uses' => 'AuthController@getLogin'));

Route::get('/establishment', array('uses' => 'EstablishmentController@getEstablishments'));
Route::post('/establishment', array('uses' => 'EstablishmentController@postEstablishments'));

Route::get('/kill-session', function(){
	Session::flush();
});

Route::get('/logout', array('uses' => 'AuthController@logout'));

/*
** All routes which require the users to be logged in and an establishment to be chosen 
*/
Route::group(array('before' => 'auth|establishment'), function(){
	Route::get('/', array('uses' => 'HomeController@showHomepage'));

	Route::group(array('prefix'=>'products'), function(){
		Route::get('/', array('uses' => 'ProductController@showProducts'));
		Route::get('/a', array('uses' => 'ProductController@getAllProducts'));
		Route::get('/{id}', array('uses' => 'ProductController@getProduct'));
	});	

	Route::get('/establishmentMap', function() {
		$id = Session::get('establishment');
		$floors = Floor::where('establishment_id', '=', $id)->get();
		return View::make('frontend.establishmentMap', array('floors' => $floors));
	});

	Route::get('/productsDetails', function() {
		return View::make('frontend.productsDetails');
	});

	Route::get('/position', function(){
		return View::make('frontend.position');
	});

    Route::group(array('prefix'=>'pathfinding'), function(){
        Route::get('/',function(){
            return View::make('frontend.pathfinding');});
    });
	

    Route::group(array('prefix'=>'route'), function(){
        Route::get('/',function(){
            return View::make('frontend.route');});
        Route::get('/scan',function(){
            return View::make('frontend.route');});
        Route::get('/{x}/{y}/{floor}',function(){
            return View::make('frontend.route');});
		Route::post('/customStartpoint', array('uses'=>'RouteController@postStartPoint'));
    });

	Route::get('/faq', function(){
		$faqs = FAQ::where('faq_visible', '=', 1)->get();
		
		return View::make('frontend.faq', array('faqs'=>$faqs));
	});
	
	Route::group(array('prefix'=>'wishlist'), function(){
		Route::get('/', array('uses' => 'WishlistController@showWishlist'));
		Route::get('/add/{id}', array('uses' => 'WishlistController@addProductToList'));
		Route::get('/delete/{id}', array('uses' => 'WishlistController@deleteProduct'));
		Route::get('/toggle/{id}', array('uses' => 'WishlistController@toggleProduct'));
	});

	Route::group(array('prefix'=>'route'), function(){
		Route::get('/{x}/{y}/{floor}', array('uses' => 'RouteController@setStartPoint'));
		Route::get('/product/{productId}', array('uses' => 'RouteController@setProductEndPoint'));
		Route::get('/scan', array('uses' => 'RouteController@showQrPage'));
		route::get('/show', array('uses' => 'RouteController@showRoute'));

	});
	

});

/* Routes back-end positioning */
Route::group(array('prefix' => 'admin/positioning', 'before'=>'basicAuth|hasPermissions:product-management'), function(){
	Route::get('/grid', array('as' => 'admin.positioning.grid', 'uses' => 'PositioningController@showGridPage'));
	Route::get('/floor/{floorId}', array('uses' => 'PositioningController@getFloor'));
	Route::post('/floor/{floorId}', array('uses' => 'PositioningController@setFloorGrid'));
	Route::get('/products', array('as' => 'admin.positioning.products', 'uses' => 'PositioningController@showProductPositioningPage'));
	Route::post('/products/get', array('uses' => 'PositioningController@getProducts'));
	Route::post('/products/coordinates', array('uses' => 'PositioningController@getProductCoordinates'));
	Route::post('/products/save', array('uses' => 'PositioningController@updateProductCoordinates'));
});




/*Error pages */
Route::get('/404error', function(){
		return View::make('error.404error');
});

Route::get('/scanerror', function(){
    return View::make('error.scanerror');
});

Route::get('/dbError', function(){
		return View::make('error.dbError');
});

Route::get('/error', function(){
		return View::make('error.error');
});

View::composer('syntara::layouts.dashboard.master', 'AdministratorMenuComposer');
Config::set('syntara::views.dashboard-index', 'admin.syntaraDashboard');
