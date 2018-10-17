<?php

namespace Acme\Domains\Users\Models;

use Kalnoy\Nestedset\NodeTrait;
use FiveSay\Laravel\Model\ExtTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Tightenco\Parental\ReturnsChildModels;
use CawaKharkov\LaravelBalance\Models\UserBalance;
use Acme\Domains\Users\Traits\HasSchemalessAttributes;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;
use CawaKharkov\LaravelBalance\Interfaces\UserHasBalance;



class User extends Model implements UserHasBalance
{
	use ExtTrait, NodeTrait, HasRoles, ReturnsChildModels;

    use HasSchemalessAttributes, UserBalance;

    protected $guard_name = 'web';

    protected $rules = [
        'mobile' => [
            'required' => 'mobile field is required',
        ],
    ];

    protected $fillable = [
		'mobile',
		'name',
	];

    public $casts = [
        'extra_attributes' => 'array',
    ];

    public function transactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    public function balance()
    {
        $income = $this->transactions()
            ->where(['type' => 100,'accepted' => 1])
            ->sum('value');

        $outcome = $this->transactions()
            ->where(['type' => 200])
            ->sum('value');

        $outcomePay = $this->transactions()
            ->where(['type' => 500,
                'accepted' => 1])
            ->sum('value');

        return (int)($income - $outcome - $outcomePay);
    }
}
