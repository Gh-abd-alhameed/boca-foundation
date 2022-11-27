<?php
defined("ABSPATH") or die('');

// ======== Import File =========
require __DIR__ . "/api.php";

use boca\core\settings\Hooks;

Hooks::Init("admin_menu", function () {
    Hooks::action(function () {
        add_menu_page(
            'Boca theme option',
            'Boca',
            'manage_options',
            'boca_settings',
            'boca_general_settings'
        );
        add_submenu_page(
            'boca_settings',
            'General Settings',
            'General Settings',
            'manage_options',
            'boca_submenu_settings',
            'boca_general_settings'
        );
        add_submenu_page(
        	"boca_settings",
			"Register Post Type",
			"Post Type",
			"manage_options",
			"boca_submenu_register_post_type",
			"boca_submenu_register_post_type_callback"
		);
        add_submenu_page(
        	"boca_settings",
			"Language",
			"Language",
			"manage_options",
			"boca_submenu_Language",
			"boca_submenu_Language"
		);
        Hooks::Init("admin_init", function () {
            Hooks::action(function () {
                add_settings_section('boca-general-options', '', 'boca_section_general_theme_settings', 'boca_settings');
                // rest api prefix
                register_setting('boca-settings-option', 'boca_register_rest_api_prefix', 'register_settings_boca_register_rest_api_prefix_callback');
                add_settings_field('boca-field-rest-api-prefix', 'Rest Api Prefix', 'boca_settings_field_rest_api_prefix', 'boca_settings', 'boca-general-options');
            });
        });
    });
});

// ========= Register Settings Callback ===========
function register_settings_boca_register_rest_api_prefix_callback($value)
{
    return $value;
}

//=========== Settings Field Callback =============
function boca_settings_field_rest_api_prefix()
{
    $data = get_option("boca-settings-option");
    $output = <<<HTML
    <input type='text' name="" id="" value="$data" />
HTML;
echo $output;
}

//========== Section Render =============
function boca_section_general_theme_settings()
{
	$output = <<<HTML
 <h1>General Setting</h1>
HTML;
	echo $output;
}
// ======== Add Admin Page ==============
function boca_general_settings() : string
{
   return require __DIR__ ."/pages/general-settings.php";
}
function boca_submenu_register_post_type_callback()
{
	return require __DIR__ ."/pages/Register-Post-type.php";
}
function boca_submenu_Language()
{
	return require __DIR__ ."/pages/Language.php";
}
