<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Middleware\Dialogflow;
use Acme\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('Start conversation', BotManController::class.'@startConversation');

$dialogflow = Dialogflow::create('b9490b2473084fc8b15a3940580f2acc')->listenForAction();

$botman->middleware->received($dialogflow);

$botman->fallback(function (BotMan $bot){
    return $bot->reply($bot->getMessage()->getExtras('apiReply'));
});