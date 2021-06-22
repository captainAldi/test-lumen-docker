<?php

use Illuminate\Http\Request;
use Telegram\Bot\Api;

use App\Jobs\SendChatJob;
use Illuminate\Support\Facades\Redis;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Cek Redis
$router->get('/redis', function () use ($router) {
    try{
        $redis = Redis::connect('127.0.0.1',6379);
        return response('redis working');
    }catch(\Predis\Connection\ConnectionException $e){
        return response('error connection redis');
    }
});


// Get Updates
$router->get('/bot/getupdates', function () use ($router) {

    $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

    $response = $telegram->getMe();

    return response()->json([
        'message' => 'Data Berhasil di Ambil !',
        'data'     => $response
    ], 200);
});


// sendMessages
$router->get('/bot/sendmessages', function () use ($router) {

    $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

    $response = $telegram->sendMessage([
        'chat_id' => env('TELEGRAM_CHAT_ID'), 
        'text' => 'Hello World'
    ]);

    return response()->json([
        'message' => 'Data Berhasil di Kirim !',
        'data'     => $response
    ], 200);
});

// sendMessages with Job
$router->get('/bot/job/sendmessages', function () use ($router) {

    $textChat = "Ini Chat dari Job / Queue";

    dispatch(new SendChatJob($textChat));

    return response()->json([
        'message' => 'Data Berhasil di Kirim via Jobs !',
    ], 200);
});

