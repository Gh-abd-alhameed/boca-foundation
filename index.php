<?php

defined("ABSPATH") or die('');

require __DIR__ . "/vendor/autoload.php";

use boca\core\settings\Init;
use boca\core\settings\Route;
Init::setapp([
	'url' => "/",
	"rest_api_prefix"=>"/boca",
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
Route::Init("api", function () {
	Route::post("/test-api", function () {
		return response()->json([
			"status" => 200,
			"data" => [
				"id" => 157,
				"name" => "test-api"
			]
		]);
	});
	Route::post("/test-api-2", function () {
		return response()->json([
			"status" => 200,
			"data" => [
				"id" => 111,
				"name" => "test-api-2"
			]
		]);
	});
});