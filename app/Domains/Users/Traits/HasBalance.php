<?php

namespace Acme\Domains\Users\Traits;

use CawaKharkov\LaravelBalance\Models\UserBalance;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;

trait HasBalance
{
    use UserBalance;

    public function transactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }
}