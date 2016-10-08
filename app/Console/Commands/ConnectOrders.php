<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pheal\Pheal;
use Log;
use App\WalletJournals;
use App\User;

class ConnectOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'connectOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connects orders from journal to sell_list';

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
        $users = User::all();
        foreach($users as $user) {
            foreach($user->SellList as $item){
                if($item->left<=0) continue;
                $records = WalletJournals::orderBy('transactionDateTime','DESC')
                  ->where('transactionDateTime','>',$item->created_at)
                  ->whereNull('sell_id')
                  ->where('transactionType','sell')
                  ->where('typeID',$item->typeID)
                  ->get();
                  //if($item->typeID == 4247) dd($item->created_at);
                foreach($records as $record)
                {
                    if($item->left - $record->quantity < 0) continue;

                    $sum = 0;
                    $quantity = 0;
                    
                    $record->sell_id = $item->id;
                    $record->save();

                    foreach($item->WalletJournals as $transaction) {
                      $quantity += $transaction->quantity;
                      $sum += $transaction->price * $transaction->quantity;
                    }
                    
                    $item->left -= $record->quantity;
                    $item->avgProfit = ($item->price) - ($sum/$quantity);
                    $item->totalProfit = $sum - ($item->price * $quantity);
                    $item->save();
                }
            }
        }
        Log::info('Journal and orders are connected!');
    }
}
