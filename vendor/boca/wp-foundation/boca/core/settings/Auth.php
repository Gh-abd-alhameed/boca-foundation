<?php


namespace boca\core\settings;

use boca\core\settings\Init;
use boca\core\settings\session;

class Auth
{
	public $login;

	public $logout;

	public $check;

	public static $type;

	public static function Type(string $type = "web")
	{
		if (!key_exists($type, Init::$app["Auth"][$type])) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Auth Not Found");
			}
		}
		self::$type = $type;
		return new Auth;
	}

	public function user()
	{
		return session::get(self::$type);
	}

	public function login($user)
	{
		if (!is_array($user)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Auth Login \$user must be array ");
			}
		}
		return session::set([self::$type => $user]);
	}

	public function logout()
	{
		return session::clear(self::$type);
	}

	public function check()
	{
		return session::has(self::$type);
	}
}