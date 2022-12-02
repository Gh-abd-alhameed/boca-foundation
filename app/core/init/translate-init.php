<?php
defined("ABSPATH") or die('');
use boca\core\settings\Locale;
use boca\core\settings\Hooks;

$code_locale = Locale::LocaleCode();
$locale = Locale::get() ;
Hooks::Init("init", function () use ($code_locale) {
	Hooks::action(function () use ($code_locale) {
		if (!is_admin()) {
			if(get_locale() != $code_locale){
				switch_to_locale($code_locale);
			}
		}
	});
});
$path_language = app("dir_language") . "/boca/$locale/translate.mo";
if (file_exists($path_language)):
	load_textdomain("bocadomain", $path_language);
endif;