<?php

use boca\core\settings\Init;
use boca\core\settings\Locale;
use boca\core\settings\Request;
use \boca\core\settings\Response;
use \boca\core\settings\Redirect;
use boca\core\settings\session;

if (!function_exists("strip_all_tags")) {
	function strip_all_tags($string, $remove_breaks = false)
	{
		$string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
		$string = strip_tags($string);
		if ($remove_breaks) {

			$string = preg_replace('/[\r\n\t ]+/', ' ', $string);
		}

		return trim($string);
	}
}

if (!function_exists("response")) {
	function response()
	{
		return new Response;
	}
}
if (!function_exists("redierct")) {
	function redierct()
	{
		return new Redirect();
	}
}
if (!function_exists("echo_token_app_meta")) {
	function echo_token_app_meta()
	{
		if (session::has("_token_app")) {
			echo '<meta  name="_token" content="' . session::get("_token_app") . '"/>';
		}
	}
}
if (!function_exists("echo_token_app_input")) {
	function echo_token_app_input()
	{
		if (session::has("_token_app")) {
			echo '<input type="text" name="_token_app" value="' . session::get("_token_app") . '" />';
		}

	}
}
if (!function_exists("dir_site")) {
	function dir_site($path = ''): string
	{
		return  Init::$app["dir_theme"] . $path;
	}
}

if (!function_exists("url_site")) {
	function url_site(bool $prefix_Locale = true): string
	{
		$url_site = Request::http() . Request::host();
		if ($prefix_Locale) {
			$locale = Locale::get();
			return $url_site . preg_replace("/\/$/", "", Init::$app["available_locales"][$locale]["prefix"]);
		}
		return $url_site . preg_replace("/\/$/", "", Init::$app["url"]);
	}
}
if (!function_exists("url")) {
	function url(string $path = "", bool $prefix_Locale = false)
	{
		return url_site($prefix_Locale) . $path;
	}
}
if (!function_exists("get_locale")) {
	function get_locale()
	{
		if (isset($_SESSION['lang']) && $_SESSION['lang'] == "ar") {
			return $_SESSION['lang'];
		}
		return "en";
	}
}
if (!function_exists("app")) {
	function app(string $key = "")
	{
		if (!is_string($key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: app key Most be string");
			}
		}
		$app = Init::$app;
		$key = empty($key) ? $app : $app[$key];
		return $key;
	}
}
if (!function_exists("component")) {
	function component(string $name, array $data = [])
	{
		if (!is_string($name)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: component name most be String");
			}
		}
		if (empty($name)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: component most be not empty");
			}
		}
		$path = explode(".", $name);

		$name_file = realpath($path);
		$name = str_replace(".php", "", $name);

		if (!file_exists(components . "/" . $name . ".php")) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: component Not Found");
			}
		}
		if (count($data) > 0) {
			foreach ($data as $key => $value) {
				${$key} = $value;
			}
		}
		require components . "/" . $name . ".php";
	}
}
if (!function_exists("view")) {
	function view(string $view, array $data = [])
	{
		if (!is_string($view)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: (view) \$view Most be String");
			}
		}
		$view = explode(".", $view);

		$view = dir_site("/resource/views/" . join("/", $view) . ".php");

		if (!file_exists($view)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: (view) file Not Found");
			}
		}
		if (count($data) > 0) {
			foreach ($data as $key => $value) {
				${$key} = $value;
			}
		}
		$view = require $view;
		return;
	}
}
if (!function_exists("request")) {
	function request()
	{
		return new \boca\core\settings\Request();
	}
}
if (!function_exists("assets")) {
	function assets($path)
	{
		if (!is_string($path)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: (assets) \$path most be string");
			}
		}
		if (!file_exists(ROOT . "/assets$path")) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: (assets) " . ROOT . "/assets$path" . " File Not Found");
			}
		}
		echo Init::$app["url_theme"] . "/assets$path";
	}
}
if (!function_exists("_trans")) {
	function _trans(string $Keyword, string $default = "")
	{
		if (!is_string($Keyword)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: _trans \$Keyword Most be String");
			}
		}
		if (!strpos($Keyword, ".")) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: _trans \$Keyword error handle");
			}
		}
		$Keyword = explode(".", $Keyword);
		$root = Init::$app["dir_theme"] . "/Languages/" . Locale::get();
		$key_trans = end($Keyword);
		if (empty($key_trans)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: _trans Key array empty");
			}
		}
		unset($Keyword[count($Keyword) - 1]);
		if (!file_exists($root . "/" . join("/", $Keyword) . ".php")) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: _trans file Not Found");
			}
		}

		$traans_file = require $root . "/" . join("/", $Keyword) . ".php";
		if (!array_key_exists($key_trans, $traans_file)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: _trans Key Not Fount ");
			}
		}
		return $traans_file[$key_trans];
	}
}
if (!function_exists("saveImageWebp")) {
	function saveImageWebp($path_image, $path_output, $name_output)
	{
		$image = imagecreatefromstring(file_get_contents($path_image));
		ob_start();
		imagejpeg($image, null, 100);
		$cont = ob_get_contents();
		ob_end_clean();
		imagedestroy($image);
		$content = imagecreatefromstring($cont);
		$output = $path_output . $name_output;
		imagewebp($content, $output);
		imagedestroy($content);
	}
}