<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Pheal\Pheal;
use Log;
use App\WalletJournals;
use App\ApiKeys;
use App\User;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\ConnectOrders::class,
        Commands\UpdateJournal::class,
        Commands\GenerateStatistics::class,
        Commands\WalletQuery::class,
        Commands\RedisFill::class,
        Commands\UpdateMarket::class,
        Commands\GetBlueprints::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * TODO Turn on-off from controll panel
         */
        $schedule->command('updateJournal')->everyThirtyMinutes()->after(function(){
            $cn = new Commands\ConnectOrders();
            $cn->handle();
        });

        $schedule->command('generate-daily')->dailyAt('01:00');
        //$schedule->command('get:blueprint')->monthly();
        $schedule->command('redis:cache')->daily();
        $schedule->command('update-market')->everyThirtyMinutes();
    }
}
