<?php

namespace App\Console\Commands;

use App\Blueprints;
use App\Items;
use Illuminate\Console\Command;

class GetBlueprints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:blueprints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the blueprnits table';

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
        Blueprints::truncate();
        $blueprints = Items::where('name','LIKE','%Blueprint')->get()->toArray();
        foreach($blueprints as &$blueprint) {
            $item = Items::where('name',str_replace(" Blueprint", "", $blueprint['name']))->first();
            if(!$item) continue;
            Blueprints::create([
                'typeID' => $blueprint['id'],
                'typeName' => $blueprint['name'],
                'itemID'=> $item->id
                ]);
        }
    }
}
