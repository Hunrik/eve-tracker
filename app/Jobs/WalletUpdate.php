<?php
namespace App\Jobs;

use App\User;
use App\Jobs\Job;
use App\Wallet;
use Pheal\Pheal;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WalletUpdate extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $apis = $this->user->ApiKeys;
      $wallet = $this->user->Wallet()->orderBy('created_at','DESC')->first();
      if((strtotime(date('Y-m-d H:i:s')) - strtotime((string)$wallet->created_at))/60 < 60) return;
      $sum = 0;
      foreach($apis as $api) {
        foreach(json_decode($api['characters']) as $character) {
            $pheal = new Pheal($api['key'], $api['vCode'], 'char');
            try {
                $response = $pheal->CharacterSheet(array("characterID" => $character));
                $sum += $response->balance;
            } catch (\Pheal\Exceptions\PhealException $e) {
                Log::error('Error when updating journal',[
                    'Error type' => get_class($e),
                    'Error Message' =>  $e->getMessage()
                ]);
            }
          }
      }
      $wallet = new Wallet();
      // TODO Fix ballance to balance
      $wallet->ballance = $sum;
      $wallet->user_id = $this->user->id;
      $wallet->save();
    }
}
