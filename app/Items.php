<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'items';

    protected $fillable = ['id','name'];

    protected $visible = ['id', 'name'];

    public $timestamps = false;

    public function scopeFindByName($query, $name)
    {
        return $query->where('name',$name);
    }

}
