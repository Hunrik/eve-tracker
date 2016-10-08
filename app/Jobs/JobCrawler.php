<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\SellList;
use App\WalletJournals;
use Log;
class JobCrawler extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->jobs = SellList::all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(SellList::all() as $job) {
          $journals = $job->WalletJournals;
          $totalSold = 0;
          $totalValue = 0;
          foreach($journals as $journal) {
            $totalSold += $journal->quantity;
            $totalValue += $journal->price * $journal->quantity;
          }
          if($job->quantity - $totalSold < 0 || $job->left != $totalSold) {
            Log::error('#'.$job->id.' The items were miscounted!!! Quantity: '.$job->quantity.' Total sold: '.$totalSold. 'Left: '. $job->left);
            $job->left = $job->quantity - $totalSold;
            $job->save();
          }
          if($totalSold === 0) continue;
            /*$job->avgProfit = ($totalValue/$totalSold) - $job->price;
            $job->totalProfit = $totalValue - ($job->price * $totalSold);
            $job->save();*/
        }
    }
}
