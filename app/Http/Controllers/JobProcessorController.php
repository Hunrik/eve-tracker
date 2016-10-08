<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SellList;
use Auth;
use App\Items;
use App\User;
use App\WalletJournals;
use Redis;

class JobProcessorController extends Controller
{
    /**
     * Show the new job form
     *
     * @return Response
     */
    public function index()
    {
        $jobs = Auth::user()->SellList;
        foreach ($jobs as &$job) {
            $items = $job->WalletJournals->toArray();
            if (!$items) {
                $job['avgProfit'] = 0;
                continue;
            }
            $all = 0;
            $quantity = 0;
            $totalProfit = 0;
            foreach ($items as $item) {
                $all += $item['price'] * $item['quantity'];
                $quantity += $item['quantity'];

            }

            ($quantity === 0 ? dd($job) : '');
            $avgProfit = ($all / $quantity) - $job->price;
            $job['avgProfit'] = $avgProfit;
            $job['totalProfit'] = $all-($job->price*$quantity);

        }
        $stats = Auth::user()->DailyStatistics->sortByDesc('day')->take(7)->toArray();
        $journal = WalletJournals::where('user_id',Auth::user()->id)->where('transactionDateTime','LIKE',date('Y-m-d'.'%'))->whereNotNull('sell_id')->get();
        $profit = 0;
        foreach($journal as $transaction) {
            $order = SellList::Find($transaction->sell_id);
            $quantity = $transaction->quantity;
            $profit += ($transaction->price * $quantity) - ($order->price*$quantity);
        }
        $today = [
            'day' => date('Y-m-d'),
            'profit' => (string)$profit
        ];
        array_push($stats,$today);
        Redis::set('user:'.Auth::user()->id.':profitToday',$profit);
        return view('jobs', [
            'jobs' => $jobs->sortByDesc('created_at'),
            'stats' => json_encode($stats)
        ]);
    }
    public function show($id){
        $order = SellList::findOrFail($id);
        return $order;
    }
    public function create(Request $request)
    {

        $db = new SellList();
        $db->user_id = Auth::user()->id;
        $db->typeName = $request->name;
        $db->typeId = Items::findByName($request->name)->first()->id;
        $db->quantity = $request->quantity;
        $db->left = $request->quantity;
        $db->price = $request->price;
        $db->save();
        return redirect('/orders')->with('status', 'Added!');
    }

    private function parse_items($input)
    {
        $arr = preg_split('#\n\s*\n#Uis', $input);
        $arr = $arr[0];
        $arr = str_replace("\r", '', $arr);
        $arr = explode("\n", $arr);
        unset($arr[0]);
        foreach ($arr as &$row) {
            $row = explode(',', $row);
        }
        unset($arr[1]);
        $arr = array_values($arr);
        if (count($arr[0]) != 8) {
            return null;
        }
        $parsed = [];
        foreach ($arr as $item) {
            $new = [
                'name' => (string)$item[0],
                'quantity' => (int)$item[1],
                'cost' => (int)$item[5],
            ];
            array_push($parsed, $new);
        }
        return $parsed;
    }

    public function connectOrders()
    {
        $users = User::all();
        foreach ($users as $user) {
            foreach ($user->SellList as $item) {
                if ($item->left <= 0) continue;
                $records = WalletJournals::sells()->orderBy('transactionDateTime', 'DESC')->where('transactionDateTime', '>', $item->created_at)->where('typeID', $item->typeID)->get();
                foreach ($records as $record) {
                    if ($item->left <= 0) break;
                    if ($record->sell_id != null) break;
                    $record->sell_id = $item->id;
                    $record->save();
                    $item->left -= $record->quantity;
                    $item->save();
                }
            }
        }
        return;
    }
}
