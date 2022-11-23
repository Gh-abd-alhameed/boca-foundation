<?php

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

// Setup File Path on your config files
// Please note that as of the V6.1 the "path" config
// can also be used for Unix sockets (Redis, Memcache, etc)
CacheManager::setDefaultConfig(new ConfigurationOption([
	'path' => WP_CONTENT_DIR . '/boca_cache', // or in windows "C:/tmp/"
]));

// In your class, function, you can call the Cache
$GLOBALS["Cache"] = CacheManager::getInstance('files');


