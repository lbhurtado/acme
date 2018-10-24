<?php

namespace Acme\Domains\Messenger\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Acme\Domains\Users as Users;
use Acme\Http\Controllers\Controller;
use Acme\Domains\Secretariat\Models\{Placement, Tag};

class SecretariatController extends Controller
{
    public function tag(BotMan $bot, $arguments)
    {
    	if ($attributes = Tag::attributes($arguments)) {
	    	if (Placement::record($attributes, $this->getUpline()) != false)
	    		return $bot->reply(trans('secretariat.tag.success'));
    	}

    	$bot->reply(trans('secretariat.tag.failed'));
    }

    protected function getUpline()
    {
    	return Users\Models\Admin::first();
    }
}
