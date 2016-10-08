<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isSuperAdmin()
    {
        if($this->role === 'superAdmin') return true;
        return false;
    }


    public function apiKeys()
    {
        return $this->hasMany('App\ApiKeys');
    }
    public function walletJournals()
    {
        return $this->hasMany('App\WalletJournals');
    }
    public function SellList()
    {
        return $this->hasMany('App\SellList');
    }
    public function DailyStatistics()
    {
        return $this->hasMany('App\DailyStatistics');
    }
    public function Wallet()
    {
        return $this->hasMany('App\Wallet');
    }
    public function MarketOrders()
    {
        return $this->hasMany('App\MarketOrders');
    }


}
