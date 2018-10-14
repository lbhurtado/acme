<?php

namespace Acme\Domains\Users\Traits;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Acme\Domains\Users\Exceptions\NoRoleDefined;
use Tightenco\Parental\HasParentModel as BaseHasParentModel;

trait HasParentModel
{
    use BaseHasParentModel {
        BaseHasParentModel::bootHasParentModel as parentBootHasParentModel;
    }

    public static function bootHasParentModel()
    {
        static::parentBootHasParentModel();

        static::created(function ($model) {
        
            if (!isset(self::$role)) {

                throw new NoRoleDefined();
            }
            
            Role::findOrCreate(self::$role, $model->getGuardName());

            $model->assignRole(self::$role);            
        });
    }

    public function getGuardName()
    {
    	return $this->guard_name;
    }
}

