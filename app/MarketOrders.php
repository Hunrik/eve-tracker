<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketOrders extends Model
{
  protected $table = 'marketOrders';
  protected $primaryKey = 'orderID';
  public $timestamps = false;
  protected $fillable = [
      'orderID',
      'user_id',
      'volEntered',
      'volRemaining',
      'orderState',
      'typeID',
      'price',
      'bid',
      'issued'];
  public function user()
  {
      return $this->belongsTo('App\User');
  }
  public function scopeSell($query) {
      return $query->where('bid',false);
  }
  public function scopeBuy($query) {
      return $query->where('bid',true);
  }
  public function scopeGetActive($query) {
      return $query->where('orderState',0);
  }
}
