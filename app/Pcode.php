<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Pcode extends Model
{
    protected $table = 'pcode';
    public $timestamps = false;

    public function scopeGetByPcode($query, $pcode) {
        $query->where('ZipCode',$pcode);
    }
    public function scopeGetByCity($query,$city) {
        $query->where('city',$city);
    }
}
