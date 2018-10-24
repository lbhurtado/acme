<?php

namespace Acme\Domains\Messenger\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Acme\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function help(BotMan $bot)
    {
	    $bot->reply(trans('common.help'));
    }

    public function stop(BotMan $bot)
    {
		$bot->reply(trans('common.stop'));
    }

    public function info(BotMan $bot)
    {
		$bot->reply(trans('common.info'));
    }
}
