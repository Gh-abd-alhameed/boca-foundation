<?php
defined("ABSPATH") or die('');

use boca\core\settings\Hooks;

$posts = get_option("boca-posts-types");

$posts_type = $posts ? unserialize($posts) : [];


if(count($posts_type) > 0){
    Hooks::Init("init", function () use ($posts_type) {
        Hooks::action(function () use ($posts_type) {
            foreach ($posts_type as $key => $value) {
                register_post_type($key, $value);
            }
        });
    });
}

