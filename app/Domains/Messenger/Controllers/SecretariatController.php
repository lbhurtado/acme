<?php

namespace Acme\Domains\Messenger\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Acme\Domains\Users as Users;
use Acme\Http\Controllers\Controller;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Acme\Domains\Secretariat\Models\{Placement, Tag, Register};
use Acme\Domains\Secretariat\Events\UserWasFlagged;

class SecretariatController extends Controller
{
    public function tag(BotMan $bot, $arguments)
    {
    	if ($attributes = Tag::attributes($arguments)) {
	    	if (Placement::record($attributes, $this->getUpline()) != false)
	    		return $bot->reply(trans('secretariat.tag.success'));
    	}

    	$bot->reply(trans('secretariat.tag.failed'));
    }

    protected function getUpline()
    {
    	return Users\Models\Admin::first();
    }

    public function register(BotMan $bot, $arguments)
    {
        if ($attributes = Register::attributes($arguments)) {
            $code = array_pull($attributes, 'code');
            $model = Placement::activate($code, $attributes);

            // $question = Question::create(trans('registration.input.pin'));
            // $bot->ask($question, function (Answer $answer) use ($bot) {
            //     $bot->reply('AAA');
            // });

            $bot->ask(trans('registration.input.pin'), [
                [
                    'pattern' => '\d{6}',
                    // 'callback' => function () {
                    //     $this->say('Okay - we\'ll keep going');
                    // }
                ],
                [
                    'pattern' => 'nah|no|nope',
                    // 'callback' => function () {
                    //     $this->say('PANIC!! Stop the engines NOW!');
                    // }
                ]
            ]);


            // event(new UserWasFlagged($this->model));
            // dd($model);            
        }

    }
}
