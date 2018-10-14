<?php
use Acme\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');
