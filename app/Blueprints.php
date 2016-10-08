<?php

namespace App;

use Redis;
use Illuminate\Database\Eloquent\Model;

class Blueprints extends Model
{
    protected $table = 'blueprints';
    public $timestamps = false;
    protected $primaryKey = 'typeID';
    public $incrementing = false;

    protected $fillable = [
        'typeID',
        'typeName',
        'itemID'
    ];
    public function Material() {
        return $this->hasMany('App/BlueprintMaterials','typeID','typeID');
    }
    public function Time() {
        return $this->hasMany('App/BlueprintTime','typeID','typeID');
    }
}


