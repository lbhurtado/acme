<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models as Models;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;

class BalanceTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function user_model_has_balance()
    {
    	$user = factory(Models\User::class)->create();

        $this->assertEquals($user->balance(), 0);
    }

    /** @test */
    public function user_model_can_transact_in_2_steps()
    {
    	$user = factory(Models\User::class)->create();
    	$refill = $amount = 500;
    	$hash = uniqid('transaction_', true);
        $transaction = BalanceTransaction::make([
            'value' => $refill,
            'hash' => $hash,
            'type' => BalanceTransaction::CONST_TYPE_REFILL,
        ])->user()->associate($user);
        $transaction->save();

        $this->assertDatabaseHas('transactions', [
        	'user_id' => $user->id, 
        	'hash' => $hash
        ]); 
        $this->assertEquals($user->balance(), 0);

        $transaction->accepted = true;
        $transaction->save();

        $this->assertEquals($user->balance(), $refill);

        $payment = $value = 100;
    	$hash = uniqid('transaction_', true);
    	$type = BalanceTransaction::CONST_TYPE_PAYMENT;
		tap(BalanceTransaction::make(compact('value', 'hash', 'type')), function($transaction) {
	        $transaction->accepted = true;
	    })->user()->associate($user)->save();

        $this->assertEquals($user->balance(), $amount - $payment);

        $checkout = $value = 50;
    	$hash = uniqid('transaction_', true);
    	$type = BalanceTransaction::CONST_TYPE_CHECKOUT;
		tap(BalanceTransaction::make(compact('value', 'hash', 'type')), function($transaction) {
	        $transaction->accepted = true;
	    })->user()->associate($user)->save();

        $this->assertEquals($user->balance(), $amount - $payment - $checkout);
    }
}
