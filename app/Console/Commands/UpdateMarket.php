<?php

namespace App\Console\Commands;


use Redis;
use App\Items;
use Illuminate\Console\Command;

class UpdateMarket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-market';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads market data';

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
        $start = microtime(true);
        // Fetch market data for jita and amarr from eve-marketdata.com
        $jitaJson = file_get_contents('http://eve-marketdata.com/api/item_prices2.json?char_name=envex&solarsystem_ids=30000142&buysell=s');

        $amarrJson = file_get_contents('http://eve-marketdata.com/api/item_prices2.json?char_name=envex&solarsystem_ids=30002187&buysell=s');

        $requestEnd = microtime(true);
        // Convert them to array
        $jitaPrices = json_decode($jitaJson,true);
        $amarrPrices = json_decode($amarrJson,true);

        // Iterate through the dump and paste it in Redis
        foreach($jitaPrices['emd']['result'] as $price) {
            Redis::set('items:'.$price['row']['typeID'].':price:jita',$price['row']['price']);
        }
        foreach($amarrPrices['emd']['result'] as $price) {
            Redis::set('items:'.$price['row']['typeID'].':price:amarr',$price['row']['price']);
            $jita = Redis::get('items:'.$price['row']['typeID'].':price:jita');
            $avg = ($price['row']['price']+$jita)/2;
            Redis::set('items:'.$price['row']['typeID'].':price:avg',$avg);
        }
        print_r("\nRequest: ");
        print_r(microtime(true) - $requestEnd);
        print_r("\nTotal: ");
        print_r(microtime(true)-$start);
    }
}
