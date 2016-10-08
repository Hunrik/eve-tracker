<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MarketData;
use League\Flysystem\Exception;
use ZMQContext;
use Redis;
use App\Items;
class MarketDataController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $items = MarketData::All()->toArray();
        return view('prices',[
            'items' => $items
        ]);
    }
    public function update()
    {
        /**
         * TODO Implement nice EVE-MARKETDATA import
         */
        $json = file_get_contents('http://eve-marketdata.com/api/item_prices2.json?char_name=demo&station_ids=60008494&buysell=s');
        $prices = json_decode($json,true);
        dd($prices['emd']['result'][10]);
        foreach($prices['emd']['result'] as $price) {

                $newItem = new MarketData();
                $newItem->itemId = $price['row']['typeID'];
                $newItem->price = $price['row']['price'];
                $newItem->save();

        }
        return redirect('/prices');
    }
    public function emdr() {


        //$chunk = Items::all()->pluck('id');
        $sell = Redis::hGetAll('sell');
        $buy = Redis::hGetAll('buy');
        $collection = collect([]);
        //dd($sell);
        foreach($sell as $typeID => $value)
        {
            // Test if item exist
            if($value == 0) continue;
            if(!$buy[$typeID]) continue;

            // Get market history then  uncompress
            $marketDataEncrypted = Redis::hGet('history',$typeID);
            $marketData = msgpack_unpack($marketDataEncrypted);

            // Make collection from it
            $coll = collect($marketData)->filter(function( $val ) {
                return $val['date'] > date('Y-m-d',strtotime('-1 week'));
            });
            if(!sizeof($coll)) {
                continue;
            }
            if($typeID == '20795') dd();
            // Add to the items collection
            $collection->push([
                'typeID' => $typeID,
                'typeName' => Redis::get('items:'.$typeID.':name'),
                'buy' => $buy[$typeID],
                'sell' => $value,
                'volume' => $coll->sum('volume')/7,
                'orderCount' => $coll->avg('orderCount'),
                'avgPrice' => $coll->last()['avgPrice']
                ]);
        }
        $collection = $collection->map(function ( $val ) {
            $val['profit']  = (($val['sell'] - $val['avgPrice']) + ($val['sell'] - $val['buy'])) / 2;
            $val['totalProfit'] = $val['profit'] * $val['volume'];
            $val['percent'] = ($val['profit'] / $val['sell'] * 100);
            $val['profitPerOrder'] = $val['totalProfit'] / $val['orderCount'];
            return $val;
        })->filter(function ($val) {
            $bool = $val['sell'] - ($val['sell'] * 0.01) > $val['avgPrice'];
            return $val['volume'] > 2 && $bool;
        })->sortByDesc('totalProfit');

        
        return view('toTrade',[
            'items' => $collection->take(1000)
            ]);
    }
    public function comp() {
        $amarrJson = file_get_contents('http://eve-marketdata.com/api/item_prices2.json?char_name=envex&station_ids=60008494&buysell=s');
        $jitaJson = file_get_contents('http://eve-marketdata.com/api/item_prices2.json?char_name=envex&solarsystem_ids=30000142&buysell=s');
        set_time_limit(360);
        $amarrArr =  json_decode($amarrJson, true)['emd']['result'];
        $jitaArr = json_decode($jitaJson, true)['emd']['result'];
        $amarr = [];
        $jita = [];
        foreach( $jitaArr as $key => $val ) {
            $val = $val['row'];
            if(!$val['price']) continue;
            $jita[$val['typeID']] = [
                'typeID' => $val['typeID'],
                'price' => $val['price']
                ];
        }
        foreach( $amarrArr as $key => $val ) {
            $val = $val['row'];
            if(!$val['price']) continue;
            if(!array_key_exists($val['typeID'], $jita)) continue;
            $amarr[$val['typeID']] = [
                'typeID' => $val['typeID'],
                'price' => $val['price']
                ];
        }
        $total = collect([]);
        foreach ($jita as $key => $value) {
            if(!array_key_exists($key, $amarr)) {
                unset($jita[$key]);
                continue;
            }
            // Get market history then  uncompress
            $marketDataEncrypted = Redis::hGet('history',$key);
            $marketData = msgpack_unpack($marketDataEncrypted);

            // Make collection from it
            $coll = collect($marketData)->filter(function( $val ) {
                return $val['date'] > date('Y-m-d',strtotime('-1 week'));
            });
            if(!sizeof($coll)) {
                continue;
            }
            $total->push([
                'typeID' => $key,
                'typeName' => Redis::get('items:'.$key.':name'),
                'jitaPrice' => $value['price'],
                'amarrPrice' => $amarr[$key]['price'],
                'profity' =>  $amarr[$key]['price'] - $value['price'],
                'totalProfit' => ($amarr[$key]['price'] - $value['price']) *  $coll->sum('volume')/7,
                'percent' => 100 - (($value['price'] / $amarr[$key]['price']) * 100),
                'volume' =>   $coll->sum('volume')/7
                ]);
        }
        $total = $total->filter(function ($val) {

            return $val['percent'] < 100;
        })->sortByDesc('totalProfit');
        dd($total->take(20));

    }
}
