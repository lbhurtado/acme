<?php

namespace Acme\Domains\Secretariat\Models;

use Spatie\Permission\Models\Role;
use Acme\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Acme\Domains\Secretariat\Events\PlacementWasRecorded;

class Placement extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'code',
        'type',
		'message',
	];

    protected $model;

    public static function record($attributes, $user = null)
    {
        return tap(static::firstOrNew($attributes), function ($placement) use ($user) {
            if (! is_null($user))
                $placement->user()
                    ->associate($user)
                    ->save();
        });
    }

    public static function activate($code, $attributes = [])
    {
        return self::bearing($code)
            ->conjure($attributes)
            ->appendToUpline()
            ->fireEvent()
            ->getModel();
    }

    protected function upline()
    {
        return User::findOrFail($this->user->id);
    }

    protected function conjure($attributes = [])
    {
        $attributes['password'] = bcrypt(env('DEFAULT_PIN', '1234'));
        
        $this->model = $this->type::create($attributes);

        return $this;
    }

    protected function appendToUpline()
    {
        $this->upline()->appendNode($this->model);

        return $this;
    }

    protected function fireEvent()
    {
        event(new PlacementWasRecorded($this->getModel(), $this));

        return $this;
    }

    protected function getModel()
    {
        return $this->model;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function activations()
    {
        return $this->hasMany(Activation::class);
    }

    public function scopeBearing($query, $code)
    {
        return $query->where('code', $code)->firstOrFail();
    }
}
