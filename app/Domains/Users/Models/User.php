<?php

namespace Acme\Domains\Users\Models;

use Kalnoy\Nestedset\NodeTrait;
use FiveSay\Laravel\Model\ExtTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Tightenco\Parental\ReturnsChildModels;
use Acme\Domains\Secretariat\Models\Placement;
use CawaKharkov\LaravelBalance\Interfaces\UserHasBalance;
use Acme\Domains\Users\Traits\{HasBalance, HasNotifications, HasSchemalessAttributes, IsVerifiable, IsObservable};

class User extends Model implements UserHasBalance
{
	use ExtTrait, NodeTrait, HasRoles, ReturnsChildModels;

    use HasSchemalessAttributes;

    use HasBalance;

    use HasNotifications;

    use IsVerifiable;

    use IsObservable;

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

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
}
