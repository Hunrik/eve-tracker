<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletJournals extends Model
{
    protected $table = 'wallet_journals';
    protected $primaryKey = 'transactionID';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'transactionDateTime',
        'transactionID',
        'quantity',
        'typeName',
        'typeID',
        'price',
        'clientID',
        'clientName',
        'stationID',
        'stationName',
        'transactionType',
        'transactionFor',
        'journalTransactionID'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function scopeSells($query) {
        return $query->where('transactionType','sell');
    }
}
