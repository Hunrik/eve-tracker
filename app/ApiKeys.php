<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Exception;
use Pheal\Pheal;
use Log;

class ApiKeys extends Model
{
    protected $table = 'apikeys';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
