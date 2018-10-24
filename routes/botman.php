<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Middleware\Dialogflow;
use Acme\Http\Controllers\BotManController;
use Acme\Domains\Messenger\Conversations\Registration;
use Acme\Domains\Messenger\Controllers\{CommonController, SecretariatController};

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

$botman->hears('help|/help|\!', CommonController::class.'@help')->skipsConversation();

$botman->hears('stop|/stop|\s', CommonController::class.'@stop')->stopsConversation();

$botman->hears('info|/info|\?', CommonController::class.'@info')->skipsConversation();

$botman->hears('tag {arguments}', SecretariatController::class.'@tag');


$botman->hears('register|/register', function (BotMan $bot) {
    $bot->startConversation(new Registration());
})->stopsConversation();