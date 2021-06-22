<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

use Telegram\Bot\Api;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            
            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

            $response = $telegram->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'), 
                'text' => 'Chat from Task Scheduler Every Minutes !'
            ]);

        })->everyMinute();
    }
}
