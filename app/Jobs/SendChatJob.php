<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Illuminate\Support\Facades\Redis;

class SendChatJob extends Job
{
    use InteractsWithQueue, Queueable, SerializesModels;

    // public $chatID;
    public $textChat;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($textChat)
    {
        // $this->$chatID = $chatID;
        $this->textChat = $textChat;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Allow only 2 chats every 1 second
        Redis::throttle('apa_aja_boleh')->allow(2)->every(1)->then(function () {

            $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

            $response = $telegram->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'), 
                'text' => $this->textChat
            ]);

        }, function () {
            // Could not obtain lock; this job will be re-queued
            return $this->release(2);
        });
    }
}
