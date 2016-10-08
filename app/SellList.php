<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SellList extends Model
{
    protected $table = 'sell_list';
    protected $appends = ['journalRecords','sold'];
    public function WalletJournals()
    {
        return $this->hasMany('App\WalletJournals','sell_id');
    }
    public function User()
    {
        return $this->belongsTo('App\User');
    }
    public function getJournalRecordsAttribute()
    {
        return $this->WalletJournals == true;
    }
    public function getSoldAttribute()
    {
        $orders = $this->WalletJournals;
        if(!$orders) abort(404);
        $quantity = 0;
        foreach($orders as $order) {
            $quantity += $order->quantity;
        }
        return $quantity;
    }
    public function scopegetFeatured($query)
    {
        return $query->where('left','>','0')->orWhere(function($query){
          return $query->where('updated_at', '>=', date('Y-m-d',strtotime("-2 days")).' 00:00:00');
        });
    }
}
