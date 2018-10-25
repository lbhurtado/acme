<?php

namespace Acme\Domains\Messenger\Conversations;

use Acme\Domains\Users\Jobs\VerifyOTP;
use Acme\Domains\Secretariat\Models\Phone;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use Acme\Domains\Secretariat\Models\Placement;
use Acme\Domains\Secretariat\Events\UserWasFlagged;
use Acme\Domains\Secretariat\Jobs\WakePlacement;
use BotMan\BotMan\Messages\Conversations\Conversation;
use Acme\Domains\Messenger\Events\UserWasTagged;

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
        // $this->inputPIN();
    }

    protected function inputMobile()
    {
        $this->counter = 0;

        $question = Question::create(trans('registration.input.mobile'))
            ->fallback('Unable to input mobile')
            ->callbackId('registration.input.mobile')
            ;

        $this->ask($question, function (Answer $answer) {
            if (!$this->mobile = Phone::validate($answer->getText())) {

                return $this->repeat(trans('registration.input.repeat'));                
            }

            // if (++$this->counter == $this->throttle) {
                
            //     return $this->bot->reply(trans('registration.break'));
            // }

            $this->inputCode();
        });
    }    

    protected function inputCode()
    {
    	$this->counter = 0;

    	$question = Question::create(trans('registration.input.code'))
            ->fallback('Unable to input code')
            ->callbackId('registration.input.code')
            ;;

        $this->ask($question, function (Answer $answer) {
            $code = $answer->getText();
            $attributes = [
                'mobile' => $this->mobile
            ];

            // WakePlacement::dispatch($code, $attributes);
            // event(new UserWasTagged($code, $attributes));
            // $this->wakePlacement($code, $attributes);


            // if ($this->model == null) {
            //     if ( ++$this->counter == $this->throttle) {
                    
            //         return $this->bot->reply(trans('registration.failed')); 
            //     }

            //     return $this->repeat(trans('registration.input.repeat'));   
            // }

            // $this->bot->reply($this->message);

            // event(new UserWasFlagged($this->model));
            sleep(1);


            $this->ask(Question::create('input you PIN'), function (Answer $answer) {
       

                $this->bot->reply('yo');
            });
        });
    }

    protected function inputPIN()
    {
        $question = Question::create('input you PIN')
            ->fallback('Unable to input pin')
            ->callbackId('registration.input.pin')
            ;
        ;

        $this->ask($question, function (Answer $answer) {
   

            $this->bot->reply('yo');
        });
    }

    // protected function inputPIN()
    // {
    //     $question = Question::create(trans('registration.input.pin'));

    //     $this->ask($question, function (Answer $answer) {
    //         // $otp = $answer->getText();
            
    //         // VerifyOTP::dispatch($this->model, $otp);
    //         $this->bot->reply('will authenticate' . 'asdasd');
    //         $this->authenticate();
    //     });
    // }
    
    // protected function authenticate()
    // {
    //     $this->bot->reply('refreshing');
    //     $this->model->refresh();

    //     $this->bot->reply('will verify');
    //     if (!$this->model->isVerified())
    //         return $this->inputPIN();

    //     return $this->bot->reply(trans('registration.authenticated'));
    // }

    protected function wakePlacement($code, $attributes)
    {
        // event(new UserWasTagged($code, $attributes));

        // WakePlacement::dispatch($code, $attributes);
        // optional(Placement::bearing($code)->first(), function($placement) use ($attributes) {
        //     $this->model = $placement->wake($attributes);
        //     $this->message = $placement->message;
        // });
    }
}
