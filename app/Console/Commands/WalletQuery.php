<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Wallet;
use Pheal\Pheal;
use App\ApiKeys;
use App\MarketOrders;
use Log;
class WalletQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walletQuery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Iterate through api-s and update wallets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apis = ApiKeys::all()->toArray();
        foreach($apis as $api) {
          $balance = 0;
            foreach(json_decode($api['characters']) as $character) {
                $pheal = new Pheal($api['key'], $api['vCode'], 'char');
                try {
                    $response = $pheal->MarketOrders(array("characterID" => $character));
                    foreach ($response->orders->toArray() as $order) {
                        $order['user_id'] = $api['user_id'];

                        if (!MarketOrders::find($order['orderID']))
                        {
                            MarketOrders::Create($order);
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
        Log::info('Journal update was successful.');
    }
}
