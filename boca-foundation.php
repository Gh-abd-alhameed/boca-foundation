<?php

/*
* Plugin Name: Boca Foundation
* Description: Rapidly improve the development process
* Requires at least: 5.9
* Requires PHP: 7.4
* Version: 1.0.0
* Author: Ghadeer Abd Alhameed
* Author URI: https://facebook.com/gh.abd.alhameed
* License: GPL-2.0-or-later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: Boca
*
* @package Boca-Foundation
*/


defined("ABSPATH") or die('');

class BocaFoundation
{
	public function __construct()
	{
		require __DIR__ . "/index.php";
	}

	function activate()
	{
		flush_rewrite_rules();
	}

	function deactivate()
	{
		flush_rewrite_rules();
	}

	function uninstall()
	{
	}
}

if (class_exists('BocaFoundation')) {
	$BocaFoundation = new BocaFoundation();
	register_activation_hook(__FILE__, array($BocaFoundation, 'activate'));
	register_deactivation_hook(__FILE__, array($BocaFoundation, 'deactivate'));
}