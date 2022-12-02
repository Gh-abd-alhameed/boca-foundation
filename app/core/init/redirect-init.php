<?php

defined("ABSPATH") or die('');

use boca\core\settings\Hooks;
use boca\core\settings\Redirect;

$array = get_option("boca-redirect");
$redirect = $array ? unserialize($array) : [];
if (count($redirect) > 0) :
	Hooks::Init("template_redirect", function () use ($redirect) {
		Hooks::action(function () use ($redirect) {
			foreach ($redirect as $key => $value) {
				Redirect::to($value["old"], $value["new"], $value["code"]);
			}
		});
	});
endif;