<?php

namespace boca\core\settings;

use boca\core\settings\Request;

class Locale
{
	protected static $locale;

	public function __construct()
	{

	}

	public static function get()
	{
		return self::$locale;
	}

	public static function set(string $locale)
	{
		if (!is_string($locale)) {
			die(__FILE__ . "|Line:" . __LINE__ . "|Message: Locale Most be String");
		}
		if (!key_exists($locale, Init::$app["available_locales"])) {
			die(__FILE__ . "|Line:" . __LINE__ . "|Message: Locale Not Found");
		}
		self::$locale = $locale;
	}

	public static function Init()
	{
		self::SetLocaleInit();
		add_action("init", [new Locale, "SetUrlSiteInit"]);

	}

	public static function SetLocaleInit()
	{
		$url = Request::http() . Request::host() . Request::uri();
		$pattern = "/^(http(s)?:\/\/)?(\w+.)?(\w+[\-]?\w+)(-\w+)?\.?\w+\/?((\w+)\/?)/";
		$check = preg_match($pattern, $url, $mache);
		$locale = Init::$app["available_locales"];
		if ($check) {
			if (key_exists(end($mache), $locale)) {
				self::$locale = end($mache);
			} else {
				self::$locale = app("locale");
			}
		}
		/*
		 * not use
		 */
		//if (!is_admin()):
			// global $wp_locale_switcher;
			//	$wp_locale_switcher->switch_to_locale( self::$locale );
		//endif;
	}

	public static function SetUrlSiteInit()
	{
		add_filter('option_home', [new Locale, 'replace_siteurl']);

	}

	public static function replace_siteurl($val)
	{
		return  url_site();
	}
}
