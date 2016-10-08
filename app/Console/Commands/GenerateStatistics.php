<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\SellList;
use App\WalletJournals;
use App\DailyStatistics;

class GenerateStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily statistics generation';

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
        $users = User::All();
        $today = date('Y-m-d',strtotime('-1 day'));
        //$today = '2016-02-14';
        foreach($users as $user) {
            $stat = $user->DailyStatistics->where('day','today');
            if(sizeof($stat))
               continue;
            $journal = WalletJournals::where('user_id',$user->id)->where('transactionDateTime','LIKE',$today.'%')->whereNotNull('sell_id')->get();
            $profit = 0;
            foreach($journal as $transaction) {
                $order = SellList::Find($transaction->sell_id);
                $quantity = $transaction->quantity;
                $profit += ($transaction->price * $quantity) - ($order->price*$quantity);
            }
            $stat = new DailyStatistics();
            $stat->user_id = $user->id;
            $stat->day = $today;
            $stat->profit = $profit;
            $stat->save();
        }
    }
}
