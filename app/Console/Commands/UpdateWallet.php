<?php

namespace App\Console\Commands;


use Redis;
use App\Items;
use Illuminate\Console\Command;

class RedisFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis-fill';

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
        $all = Items::all()->toArray();
        foreach($all as $item) {
            Redis::set('items:'.$item['id'],$item['name']);
        }
    }
}
