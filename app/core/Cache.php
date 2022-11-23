<?php
namespace app\core;

class Cache
{
	public static $cache;

	public function get($name)
	{
		global $Cache;
		return $Cache->getItem($name);
	}

	public static function set(string $key, array|string $value, int $exp)
	{
		global $Cache;
		return  array($key, $value, $exp);
	}

	public function delete(array $keys)
	{
		global $Cache;
		return $Cache->deleteMultiple($keys);
	}

	public function exp($seconds)
	{
		return $seconds;
	}
}