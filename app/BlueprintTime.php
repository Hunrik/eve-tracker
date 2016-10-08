<?php

namespace App;

use Redis;
use Illuminate\Database\Eloquent\Model;

class BlueprintsTime extends Model
{
    protected $table = 'industryActivity';
    public $timestamps = false;
    protected $primaryKey = 'typeID';
    public $incrementing = false;

    protected $fillable = [
        'typeID',
        'typeName',
        'itemID'
    ];
    public function Blueprints() {
        return $this->belongsTo('App/BlueprintMaterials','typeID','typeID');
    }
}


