<?php

namespace Acme\Domains\Users\Models;

use Kalnoy\Nestedset\NodeTrait;
use FiveSay\Laravel\Model\ExtTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tightenco\Parental\ReturnsChildModels;
use Acme\Domains\Secretariat\Models\Placement;
use CawaKharkov\LaravelBalance\Models\UserBalance;
use Acme\Domains\Users\Traits\HasSchemalessAttributes;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;
use CawaKharkov\LaravelBalance\Interfaces\UserHasBalance;

use Acme\Domains\Secretariat\Models\Checkin;


class User extends Model implements UserHasBalance
{
	use ExtTrait, NodeTrait, HasRoles, ReturnsChildModels;

    use HasSchemalessAttributes, UserBalance;

    use Notifiable;

    protected $guard_name = 'web';

    protected $rules = [
        'mobile' => [
            'required' => 'mobile field is required',
        ],
    ];

    protected $fillable = [
		'mobile',
		'name',
        'email',
        'type',
        'password'
	];

    public $casts = [
        'extra_attributes' => 'array',
    ];

    // protected $childTypeAliases = [
    //     'admin' => Admin::class,
    //     'operator' => Operator::class,
    //     'staff' => Staff::class,
    //     'subscriber' => Subscriber::class,
    //     'worker' => Worker::class,
    // ];

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

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForTwilio($notification)
    {
        return $this->mobile;
    }
}
