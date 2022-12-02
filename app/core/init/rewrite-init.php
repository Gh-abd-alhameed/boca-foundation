<?php
defined("ABSPATH") or die('');

use boca\core\settings\Hooks;


$array = get_option("boca-rewrite-rule");
$rewrite_rule = $array ? unserialize($array) : [];
if (count($rewrite_rule) > 0):
	Hooks::Init("init", function () use ($rewrite_rule) {
		Hooks::action(function () use ($rewrite_rule) {
			foreach ($rewrite_rule as $key => $value) {
				add_rewrite_rule($value["url"] , $value["matches"] , $value["rule"]);
			}
		});
	});
endif;