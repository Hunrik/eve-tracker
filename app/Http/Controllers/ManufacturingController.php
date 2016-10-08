<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redis;
use App\BlueprintMaterials;
use App\Items;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManufacturingController extends Controller
{
	/**
	 * Remder /manufacturing page
	 */
    public function index() {
    	$id = Items::findByName('Vexor Blueprint')->first()->id;
    	//dd(BlueprintMaterials::getMaterials(13000, 10, 'NPC',5));
    	return view('manufacturing');
    }
    /**
     * Calculate Item
     * @todo Add validation
     * @todo Select Production facility
     */
    public function store(Request $request) {
    	$id = $request->itemID;
    	$ME = $request->me;
    	$PE = $request->pe;
    	$qty = $request->quantity;
    	$name = $request->name;
    	$redis = true;
    	if( !$id ) {
    		$bp = Blueprints::where('typeName', 'LIKE', $name.'%')->first();
    		$id = $bp->typeID;
    		$name = $id->typeName;
    		if(!$id) return redirect('/manufacturing')->with('error','Item does not exist');
    	}

    	$materials = BlueprintMaterials::getMaterials($id, $ME , 'NPC' , $qty);
    	$totalPrice = 0;
    	foreach($materials as $material) {
    		$totalPrice += $material['totalPrice'];
    	}
    	
        return view('orders');
    }
}
