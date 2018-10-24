<?php

namespace Acme\Domains\Messenger\Conversations;

use Acme\Domains\Users\Jobs\VerifyOTP;
use Acme\Domains\Secretariat\Models\Phone;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Acme\Domains\Secretariat\Models\Placement;
use Acme\Domains\Secretariat\Events\UserWasFlagged;
use BotMan\BotMan\Messages\Conversations\Conversation;

class Registration extends Conversation
{
    protected $throttle;

    protected $model;

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->throttle = 3;

        $this->inputMobile();
    }

    protected function inputMobile()
    {
        $this->counter = 0;

        $question = Question::create(trans('registration.input.mobile'));

        $this->ask($question, function (Answer $answer) {
            if (!$this->mobile = Phone::validate($answer->getText())) {

                return $this->repeat(trans('registration.input.repeat'));                
            }

            if (++$this->counter == $this->throttle) {
                
                return $this->bot->reply(trans('registration.break'));
            }

            $this->inputCode();
        });
    }    

    protected function inputCode()
    {
    	$this->counter = 0;

    	$question = Question::create(trans('registration.input.code'));

        $this->ask($question, function (Answer $answer) {
            $code = $answer->getText();
            $attributes = [
                'mobile' => $this->mobile
            ];

            optional(Placement::bearing($code)->first(), function($placement) use ($attributes) {
                $this->model = $placement->wake($attributes);
                $this->message = $placement->message;
            });

            if ($this->model == null) {
                if ( ++$this->counter == $this->throttle) {
                    
                    return $this->bot->reply(trans('registration.failed')); 
                }

                return $this->repeat(trans('registration.input.repeat'));   
            }

            $this->bot->reply($this->message);

            event(new UserWasFlagged($this->model));
            $this->inputPIN();
        });
    }

    protected function inputPIN()
    {
        $this->counter = 0;

        $question = Question::create(trans('registration.input.pin'));

        $this->ask($question, function (Answer $answer) {
            $otp = $answer->getText();
            
            VerifyOTP::dispatch($this->model, $otp);
             
            $this->authenticate();
        });
    }
    
    protected function authenticate()
    {
        $this->user->refresh();

        if (!$this->user->isVerified())
            return $this->inputPIN();

        return $this->bot->reply(trans('registration.authenticated'));
    }
}
