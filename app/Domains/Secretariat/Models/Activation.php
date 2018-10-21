<?php

namespace Acme\Domains\Secretariat\Models;

use Illuminate\Database\Eloquent\Model;
use Acme\Domains\Users\Models\User;

class Activation extends Model
{
    public function placement()
    {
    	return $this->belongsTo(Placement::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
