<?php

use MrJuliuss\Syntara\Controllers\BaseController;

class PositioningController extends BaseController
{
    /**
     * Show the grid view
     */
    public function showGridPage()
    {
        $floors = Floor::all();
        $this->layout = View::make('admin.gridPositioning', array('floors' => $floors));
        $this->layout->title = 'Positionering';

        // Breadcrumbs
        $this->layout->breadcrumb = array(
            array(
                'title' => 'Positionering',
                'link' => '#',
                'icon' => 'glyphicon-map-marker'
            ),
            array(
                'title' => 'Raster',
                'link' => 'admin/positioning/grid',
                'icon' => false,
            ),
        );
    }            

    /**
     * Shows the product positioning view
     */
    public function showProductPositioningPage(){
        $floors = Floor::all();
        $this->layout = View::make('admin.productPositioning', array('floors' => $floors));
        $this->layout->title = "Product Positioneringen";

        // Breadcrumbs
        $this->layout->breadcrumb = array(
            array(
                'title' => 'Positionering',
                'link' => '#',
                'icon' => 'glyphicon-map-marker'
            ),
            array(
                'title' => 'Producten',
                'link' => 'admin/positioning/products',
                'icon' => false,
            ),
        );
    }

    /**
     * Gets all the products on a specific floor which match with the search query
     * @return array
     */
    public function getProducts(){
        // POST Values
        $floorId = Input::get('floorId');
        $search = Input::get('search');

        // Gets an array of prod_id => prod_name of the products
        // Also query the prod_name based on the search value
        // Limit of 10
        $products = Product::where('prod_name', 'like', "%$search%")->alphabetical()->take(10)->lists('prod_name', 'prod_id');

        // Return the list
        return $products;
    }

    /**
     * Gets the product coordinates on the current floor
     * Also returns other possible floors where the product is placed
     * @return array
     */
    public function getProductCoordinates(){
        $floorId = Input::get('floorId');
        $productId = Input::get('productId');
        $establishmentId = Floor::find($floorId)->establishment_id;
        
        // Gets an array of the Product_Establishment entries of the product on the current floor
        $currentFloor = Product_Establishment::where('product_id', '=', $productId)
            ->where('ppe_floor', '=', $floorId)
            ->get()->toArray();

        // Finds if the product exists on other floors in the same establishment
        $otherFloors = DB::table('product_establishment')
            ->join('floor', 'product_establishment.ppe_floor', '=', 'floor.floor_id')
            ->where('product_id', '=', $productId)
            ->where('floor.establishment_id', '=', $establishmentId)
            ->where('floor.floor_id', '!=', $floorId)
            ->lists('floor.floor_level');

        // Return the current floor entries + other floor entries (if exists)
        return array(
            'currentFloor' => $currentFloor,
            'otherFloors' => $otherFloors
        );
    }

    /**
     * Update product coordinates
     * @param array Array with ID's to delete
     * @param array Array with values of new positions
     * @return array success value
     */
    public function updateProductCoordinates(){
        $productId = Input::get('productId');
        $removeArray = Input::get('deleteArr');
        $createArray = Input::get('createArr');

        // If there are values in this array, create new positions
        if(count($createArray) > 0){
            foreach($createArray as $create){
                $pe = new Product_Establishment;
                $pe -> product_id = $productId;
                $pe -> ppe_xvalue = $create[0];
                $pe -> ppe_yvalue = $create[1];
                $pe -> ppe_floor = $create[2];
                $pe -> save();
            }
        }

        // If there are values in this array, remove positions with the corresponding ID's
        if(count($removeArray) > 0){
            foreach($removeArray as $removeId){
                $pe = Product_Establishment::find($removeId);
                $pe->delete();
            }
        }  

        return array('success' => true);
    }

    /**
     * Get floor object
     * @param int floor ID
     * @return Floor
     */
    public function getFloor($floorId){
        $floor = Floor::find($floorId);
        return $floor;
    }

    /**
     * Add grid to a specific floor
     * @param int $floorId 
     * @return array
     */
    public function setFloorGrid($floorId){
        $floorGrid = Input::get('floorGrid');

        $floor = Floor::find($floorId);
        $floor->floor_grid = $floorGrid;

        if($floor->save()){
            return array('success' => true);
        } else {
            return array('success' => false);
        }
    }

}

?>