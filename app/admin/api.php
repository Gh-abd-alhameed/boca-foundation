<?php
defined("ABSPATH") or die('');

use boca\core\settings\Request;
use boca\core\settings\Route;
use boca\core\settings\session;
use Gettext\Loader\PoLoader;
use Gettext\Generator\MoGenerator;


Route::Init("/boca/v1", function () {
	Route::post("/add-locale-default", function () {
		if (empty(Request::input("_token_app")) || (session::get("_token_app") != Request::input("_token_app"))) {
			session::set(["error" => "error 401 Auth"]);
			return redierct()->back();
		}
		if (empty(Request::input("boca-default-locale"))):
			session::set(["error" => "Input error"]);
			return redierct()->back();
		endif;
		$update = update_option("boca_language_default" ,Request::input("boca-default-locale") );
		if($update){
			session::set(["success" => "default locale ". Request::input("boca-default-locale") ." Success"]);
		}else{
			session::set(["error" => "not set ". Request::input("boca-default-locale") ." locale"]);
		}
		return redierct()->back();
	});
	Route::post("/add-translate", function () {
		if (empty(Request::input("_token_app")) || (session::get("_token_app") != Request::input("_token_app"))) {
			session::set(["error" => "error 401 Auth"]);
			return redierct()->back();
		}
		if(count(Request::body()) <= 1 )
		{
			$update = update_option("boca_language_selected", []);
			if($update){
				session::set(["success" => "Create Success"]);
			}else{
				session::set(["error" => "error create"]);
			}
			return redierct()->back();
		}
		$language = array();
		if (empty(Request::input("language"))) {
			session::set(["error" => "Input error"]);
			return redierct()->back();
		}
		foreach (Request::input("language") as $key => $value):
			$language[$key] = array(
				"name"=> $key,
				"prefix" => $value["prefix"],
				"active" => isset($value["active"]) ? "true" : "false",
				"code" => $value["code"],
			);
		endforeach;
		$update = update_option("boca_language_selected", serialize($language));
		unset($language);
		if($update){
			session::set(["success" => "Create Success"]);
		}else{
			session::set(["error" => "error create"]);
		}
		return redierct()->back();
	});
	Route::post("/translate", function () {
		if (!isset(Request::headers()["X-Token-App"]) || (session::get("_token_app") != Request::headers()["X-Token-App"])) {
			return json_encode([
				"status" => 401,
				"error" => "Auth",
				"msg" => "error Auth",
				"data" => []
			]);
		}
		//import from a .po file:
		$loader = new PoLoader();
		$translations = $loader->loadFile(app("dir_plugin") . "/Languages/messages.po");

		//export to a .mo file:
		$generator = new MoGenerator();
		$generator->generateFile($translations, app("dir_language") . "/boca/ar/messages.mo");
		return json_encode([
			"status" => 200,
			"err" => "",
			"msg" => "",
			"data" => []
		]);
	});

});