<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\WalletJournals;
use App\ApiKeys;
use Pheal\Pheal;
use Log;

class JournalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::User();
        $this->creds = $this->user->ApiKeys->toArray();
    }

    public function index()
    {
        return view('journal', [
            'entries' => $this->user->WalletJournals()->orderBy('transactionDateTime','DESC')->get()
        ]);
    }

    public function update()
    {
        $start = microtime(true);
        $apis = ApiKeys::all()->toArray();
        foreach($apis as $api) {
            foreach(json_decode($api['characters']) as $character) {
                $pheal = new Pheal($api['key'], $api['vCode'], 'char');
                try {
                    $response = $pheal->WalletTransactions(array("characterID" => $character));
                    $transactions = $response->transactions->toArray();
                    foreach ($transactions as $transaction) {
                        $transaction['user_id'] = $api['user_id'];

                        if (!WalletJournals::find($transaction['transactionID']))
                        {
                            WalletJournals::Create($transaction);
                        }
                    }
                } catch (\Pheal\Exceptions\PhealException $e) {
                    Log::error('Error when updating journal',[
                        'Error type' => get_class($e),
                        'Error Message' =>  $e->getMessage()
                    ]);
                }
            }
        }
        $stop = microtime(true);
        Log::info('Journal update was successful, Took '.$stop-$start.' to finish');

    }
}
