<?php

namespace boca\core\settings;


class session
{
	public static function Init()
	{
		session_start();
		self::token();
	}

	public static function token()
	{
		if (!session::has("_token_app")) {
			$token = openssl_random_pseudo_bytes(8);
			$token = bin2hex($token);
			self::set(["_token_app"=> $token]);
		}
	}

	public
	static function set(array $keys)
	{
		if (!is_array($keys)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Set Key Most be Array");
			}
		}
		if (!(count($keys) > 0)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Set Key Most be Not Empty");
			}
		}
		foreach ($keys as $key => $value) {
			$_SESSION[$key] = $value;
		}
		return;
	}

	public
	static function get(string $key)
	{
		if (!is_string($key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Get Key Most be String");
			}
		}
		if (empty($key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Get Key Most be Not Empty");
			}
		}
		if (!key_exists($key, $_SESSION)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Get Key Not Found");
			}
		}
		return $_SESSION[$key];
	}

	public
	static function has(string $session_key)
	{
		if (!is_string($session_key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session has Key Most be String");
			}
		}
		if (empty($session_key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session has Key Most be Not Empty");
			}
		}
		if (array_key_exists($session_key, $_SESSION)) {
			return true;
		}
		return false;
	}

	public
	function Flash(string $session_key)
	{
		if (!is_string($session_key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Flash Key Most be String");
			}
		}
		if (empty($session_key)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Flash Key Most be Not Empty");
			}
		}
		if (!key_exists($session_key, $_SESSION)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Get Key Not Found");
			}
		}
		echo $_SESSION[$session_key];

		unset($_SESSION[$session_key]);
	}

	public
	static function clear($keys)
	{
		if (is_string($keys)) {
			unset($_SESSION[$keys]);
			return;
		}
		if (is_array($keys)) {
			foreach ($keys as $key => $value) {
				unset($_SESSION[$value]);
			}
			return;
		}
		return;
	}

	public
	static function SetPrimary(array $keys)
	{
		if (!is_array($keys)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Set Key Most be Array");
			}
		}
		if (!(count($keys) > 0)) {
			if (Init::$app["debug"]) {
				die(__FILE__ . "|Line:" . __LINE__ . "|Message: Session Set Key Most be Not Empty");
			}
		}
		foreach ($keys as $key => $value) {
			if (!key_exists($key, $_SESSION)) {
				continue;
			}
			$_SESSION[$key] = $value;
		}
	}
}
