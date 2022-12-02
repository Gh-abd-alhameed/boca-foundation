<?php
defined("ABSPATH") or die('');

use boca\core\settings\Hooks;


$array = get_option("boca-rewrite-tag");
$rewrite_rule_tag = $array ? unserialize($array) : [];
if (count($rewrite_rule_tag) > 0):
	Hooks::Init("init", function () use ($rewrite_rule_tag) {
		Hooks::action(function () use ($rewrite_rule_tag) {
			foreach ($rewrite_rule_tag as $key => $value) {
				add_rewrite_tag((string)$value["tag"], (string)$value["regx"], (string)$value["query"]);
			}
		});
	});
endif;