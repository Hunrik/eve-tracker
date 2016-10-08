<?php

namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Redis;
class DailyProfit extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->jobs = $user->SellList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $profit = 0;
      foreach($this->jobs as $job) {
        $orders = $job->WalletJournals->where('transactionDateTime','LIKE',date('Y-m-d').'%');
        if(!$orders) continue;
        $quantity = $orders->sum('quantity');
        $avg = $orders->avg('price');
        $profit += ($avg - $job->price) * $quantity
      }
        Redis::set('user:'.$user->id.':profitToday',$profit);
    }
}
