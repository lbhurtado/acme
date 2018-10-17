<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Acme\Domains\Users\Constants as Constants;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        DB::table('permissions')->truncate();

        $name = Constants\UserPermission::CREATE_PLACEMENT;
        
        Permission::create(compact('name'));
    }
}
