<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketData extends Model
{
    protected $table = 'marketData';
    protected $primaryKey = 'itemId';
    public $timestamps = false;
}
