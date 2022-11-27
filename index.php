<?php

defined("ABSPATH") or die('');
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/bootstrap.php";
use boca\core\settings\Init;
use boca\core\settings\Hooks;
use boca\core\settings\Locale;

// available locales
$languages_default = [
	'en' => [
		"name" => "en",
		"prefix" => "/",
		"active" => "true",
		"code" => "en_US",
	]
];
$language_db = get_option("boca_language_selected");
$language_value = !empty($language_db) ? unserialize($language_db) : [];

$final_language = array_merge($languages_default ,$language_value );
// default Locale
$default_locale = get_option("boca_language_default");
$default_locale = $default_locale ? $default_locale : "en";

$settings = array();
$settings["url"] ="/";
$settings["dir_theme"]= get_template_directory();
$settings["url_theme"]=get_template_directory_uri();
$settings["dir_plugin"] =  plugin_dir_path(__FILE__) ;
$settings["url_plugin"] = plugin_dir_url(__FILE__);
$settings["dir_language"] = WP_LANG_DIR ;
$settings["rest_api_prefix"] = "/boca";
$settings["locale"] = $default_locale;
$settings["available_locales"] =$final_language;
$settings["static_file"] = [
	"assets" => [
		"prefix" => "/assets",
		"extension" => ["css", "js", "pdf", "webp", "png", "jpg"]
	]
];
$settings["debug"] = true;
Init::setapp($settings);
Init::init();

$code_locale = Locale::LocaleCode();
Hooks::Init("init" , function () use($code_locale) {
	Hooks::action(function () use($code_locale) {
		if(!is_admin())
		{
			switch_to_locale($code_locale);
		}
	});
});
$path_language = app("dir_language")  . "/boca/". Locale::get() ."/messages.mo" ;
load_textdomain( "boca-domain", $path_language );
require __DIR__ . "/app/function.php";