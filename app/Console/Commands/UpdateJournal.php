<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ApiKeys;
use Pheal\Pheal;
use Log;
use App\WalletJournals;

class UpdateJournal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateJournal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates journals';

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
        Log::info('Journal update was successful.');
    }
}
