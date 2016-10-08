<?php

namespace App\Console\Commands;


use Redis;
use App\Blueprints;
use App\Items;
use Illuminate\Console\Command;

class RedisFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches items id to Redis from mysql';

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
        $crest = file_get_contents('https://public-crest.eveonline.com/market/types/');
        $crest = json_decode($crest);
        $collection = $crest->items;
        $this->comment($crest->pageCount);
        for( $i=2; $i < $crest->pageCount; $i++) {
            $resp = file_get_contents('https://public-crest.eveonline.com/market/types/?page='.$i);
            $resp = json_decode($resp);
            foreach($resp->items as $item) {
                array_push($collection,$item);
            }
        }
        $db = [];
        $this->comment(microtime(true) - $start);   
        foreach($collection as $item) {
            $type = $item->type;
            Redis::set('items:'.$type->id.':name',$type->name);
            Redis::set('items:'.$type->id.':href',$type->href);
            Redis::set('items:'.$type->id.':icon',$type->icon->href);
            array_push($db,[
                'id' => $type->id,
                'name' => $type->name
            ]);
        }
        $this->comment(microtime(true) - $start);
        Items::truncate();
        Items::insert($db);
        $this->comment(microtime(true) - $start);
        Redis::set('items',json_encode($db));
        dd(sizeof($db));

        /*
        $items = Items::all()->toArray();
        $blueprints = Blueprints::all()->toArray();
        foreach($items as $item) {
            Redis::set('items:'.$item['id'].':name',$item['name']);
        }
        foreach($blueprints as $item) {
            Redis::set('items:'.$item['itemID'].':blueprint',$item['typeID']);
        }*/
    }
}
