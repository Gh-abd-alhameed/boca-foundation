<?php

defined("ABSPATH") or die('');
use boca\core\settings\Hooks;

Hooks::Init("admin_enqueue_scripts", function () {
	Hooks::action(function ($hook) {
		wp_enqueue_media();
		wp_enqueue_style('boca-bootstrap-css', plugin_dir_url(__FILE__) . '/assets/css/bootstrap.min.css', array(), '1.0.0');
		wp_enqueue_style('boca-main-css', plugin_dir_url(__FILE__) . '/assets/css/main.css', array(), '1.0.0');
		wp_enqueue_script("boca-main-js", plugin_dir_url(__FILE__) . '/assets/js/boca.min.js', array('jquery', 'media-upload'), '1.0.0', true);
	});
});