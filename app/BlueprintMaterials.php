<?php

namespace App;

use Redis;
use Illuminate\Database\Eloquent\Model;

class BlueprintMaterials extends Model
{
    protected $table = 'industryActivityMaterials';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    public function scopeManufacturing($query) {
    	return $query->where('activityID',1);
    }

    /**
     * Calculate given blueprint ID's materials,
     * based on the following parameters
     * @param  [type] $query    [Scope]
     * @param  [type] $id       [Blueprint typeID]
     * @param  [type] $ME       [Material Efficiency]
     * @param  [type] $runs     [Numbers of items to produce]
     * @param  [type] $facility [Where the production takes place]
     * @return [type]           [description]
     */
    public function scopeGetMaterials($query,$id,$ME,$facility = 'NPC',$runs = 1) {
    	/**
    	 * Facility can be:
    	 * RA => Rapid Assembly array 1.05x
    	 * NPC => NPC station 1.0x
    	 * POS => Non rapid assembly arrays on pos 0.95x
    	 */
    	$start = microtime(true);
    	$modifier = 1;
    	switch ($facility) {
    		case 'RA':
    			$modifier *= 1.05;
    			break;
    		case 'NPC':
    			break;
    		case 'POS':
    			$modifier *= 0.95;
    		default:
    			abort(500,'Invalid facility');
  	 			break;
    	}
    	$modifier = $modifier * ((100-$ME)/100);

    	$coll = [];
    	$materials = $query->Manufacturing()->where('typeID',$id)->get();
    	foreach( $materials as $material ) {
    		$materialName = Redis::get('items:'.$material->materialTypeID.':name');
    		if ( !$materialName ) $materialName = Items::findOrFail($material->materialTypeID)->name;
            $quantity = $runs*ceil($material->quantity * $modifier);
            /**
             * Collection of each material
             * @name Item name
             * @typeID Item ID
             * @quantity Item Quantity
             * @totalPrice total price based on Jita
             * TODO Jita/amarr price based on user
             */
    		$res = [
    			'name' => $materialName,
    			'typeID' => $material->materialTypeID,
    			'quantity' => $quantity,
                'totalPrice' => round(Redis::get('items:'.$material->materialTypeID.':price:jita')*$quantity,2)
    		];
    		array_push($coll, $res);
    	}
    	return $coll;
    }
}


