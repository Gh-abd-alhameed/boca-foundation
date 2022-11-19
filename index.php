<?php

defined("ABSPATH") or die('');

require __DIR__ . "/vendor/autoload.php";

use boca\core\settings\Init;

Init::setapp([
	'url' => "/",
	"dir_theme" => get_template_directory(),
	'locale' => 'en',
	'available_locales' => [
		'en' => [
			"prefix" => "/",
		],
		'ar' => [
			"prefix" => "/ar"
		]
	],
	"static_file" => [
		"public" => [
			"prefix" => "/public",
			"extension" => ["css", "js", "pdf", "webp", "png", "jpg"]
		]
	],
	"debug" => true,
	"databases" => [
		"driver" => "mysql",
		"host" => "localhost:3306",
		"database" => "shop",
		"username" => "root",
		"password" => ""
	],
]);
Init::init();



