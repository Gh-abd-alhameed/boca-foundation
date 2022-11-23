<?php

namespace boca\core\settings;

use boca\core\settings\RequestHandler;

class Request extends RequestHandler
{
	public static function uri()
	{
		return self::$uri;
	}

	public static function previous()
	{
		return self::$previous;
	}

	public static function only(array $array)
	{
		return array_intersect_key(array_merge(self::body(),self::json(),self::query()), array_flip($array));
	}

	public static function host()
	{
		return self::$host;
	}

	public static function http()
	{
		return self::$http;
	}

	public static function query()
	{
		return self::$query;
	}

	public static function param()
	{
		return self::$param;
	}

	public static function input($name)
	{
		return self::$body[$name];
	}

	public static function headers()
	{
		return self::$headers;
	}

	public static function json()
	{
		return self::$json;
	}

	public static function body()
	{
		return self::$body;
	}
	public static function  method()
	{
		return self::$method;
	}
}
