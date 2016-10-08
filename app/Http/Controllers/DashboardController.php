<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Pheal\Pheal;
use Auth;
use App\Items;
use App\SellList;
use App\DailyStatistics;
use Redis;
use App\Jobs\DailyProfit;
use App\Jobs\WalletUpdate;

class DashboardController extends Controller
{
    protected $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    public function index() {
        $i = 1;
        $do = true;
        do {
            $url = file_get_contents("https://public-crest.eveonline.com/regions/");
            $items = json_decode($url);
            dd($items);
        } while ($do);
        /**
         * TODO implement multiple keys
         */
        $user = Auth::user()->apiKeys[0];
        $phal = new Pheal($user->key,$user->vCode,'account');
        $characterID = $phal->Characters()->characters[0]['characterID'];
        $pheal = new Pheal($user->key,$user->vCode,'char');
        try {
            $response = $pheal->WalletTransactions(array("characterID" => $characterID));
        } catch (\Pheal\Exceptions\PhealException $e) {
            echo sprintf(
                "an exception was caught! Type: %s Message: %s",
                get_class($e),
                $e->getMessage()
            );
        }

        return view('dashboard',[
            'users' => User::all()->toArray(),

        ]);
    }
    public function show() {
      $this->dispatch( new DailyProfit(Auth::user()) );
      $this->dispatch( new WalletUpdate(Auth::user()) );
      return view('home',[
        'jobs' => SellList::getFeatured()->get(),
        'totalProfit' => DailyStatistics::where('user_id',Auth::user()->id)->where('day','LIKE',date('Y-m').'%')->get()->sum('profit'),
        'profitToday' => Redis::get('user:'.Auth::user()->id.':profitToday'),
        'wallet' => $this->user->wallet()->orderBy('created_at','DESC')->first()->ballance
      ]);
    }
}
