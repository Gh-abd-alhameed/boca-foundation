<?php

namespace app\core;

class Cache
{
    public static $cache;
    public static function get(string $key)
    {
        global $Cache;
        self::$cache = $Cache->getItem($key);
        return self::$cache;
    }
    public static function isHit()
    {
        return self::$cache->isHit();
    }
}