<?php

namespace Acme\Domains\Secretariat\Models;

use Spatie\Permission\Models\Role;
use Acme\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Placement extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'code',
        'type',
		'message',
	];

    public static function activate($code, $attributes = [])
    {
        $placement = static::where('code', $code)->firstOrFail();

        $attributes['password'] = env('DEFAULT_PIN', '1234');
        $user = $placement->type::create($attributes);  

        $parent = User::findOrFail($placement->user_id);

        $parent->appendNode($user);

        return $user;
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function upline()
    {
        return User::findOrFail($this->user->id);
    }
}
