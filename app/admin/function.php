<?php

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
        Hooks::Init("admin_init", function () {
            Hooks::action(function () {
                add_settings_section('boca-general-options', 'Settings', 'boca_section_general_theme_settings', 'boca_settings');
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
function boca_general_settings() : string
{
   return require __DIR__ ."/pages/general-settings.php";
}
