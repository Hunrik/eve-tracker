<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyStatistics extends Model
{

    protected $table = 'daily_statistics';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
