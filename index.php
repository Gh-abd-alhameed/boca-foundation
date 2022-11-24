<?php

defined("ABSPATH") or die('');
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/bootstrap.php";
use boca\core\settings\Init;

Init::setapp([
	'url' => "/",
	"rest_api_prefix" => "/boca",
	"dir_theme" => get_template_directory(),
	"url_theme" => get_template_directory_uri(),
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
		"assets" => [
			"prefix" => "/assets",
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
require __DIR__ . "/app/function.php";
