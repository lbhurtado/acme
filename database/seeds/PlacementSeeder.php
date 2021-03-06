<?php

use Illuminate\Database\Seeder;
use Acme\Domains\Users\Models\Admin;
use Acme\Domains\Secretariat\Models\{Placement, Tag};

class PlacementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('placements')->truncate();

        $admin = Admin::first();

        foreach(Tag::$classes as $key => $values) {
			$code = $key;
			$type = $values;
            $message = env('BOT_REGISTRATION_MESSAGE_'.strtoupper($key), 'You are now a registered '.strtolower($key).'.');
			Placement::record(compact('code', 'type', 'message'), $admin);  	
        }
    }
}
