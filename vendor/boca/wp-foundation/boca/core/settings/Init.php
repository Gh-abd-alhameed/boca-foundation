<?php


namespace boca\core\settings;

use boca\core\settings\Locale;
use boca\core\settings\session;

class Init
{
	public static array $app = [];
	public $Locale;



	public static function setapp(array $app = [])
	{
		self::$app = $app;
	}

	public static function init()
	{
		session::Init();
		Request::Init();
		Locale::Init();
	}


}