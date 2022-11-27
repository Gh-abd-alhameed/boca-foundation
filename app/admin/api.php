<?php
defined("ABSPATH") or die('');

use boca\core\settings\Request;
use boca\core\settings\Route;
use boca\core\settings\session;
use Gettext\Loader\PoLoader;
use Gettext\Generator\MoGenerator;


Route::Init("/boca/v1", function () {
    Route::post("/add-locale-default", function () {
        if (empty(Request::input("_token_app")) || (session::get("_token_app") != Request::input("_token_app"))) {
            session::set(["error" => "error 401 Auth"]);
            return redierct()->back();
        }
        if (!Request::hasInput("boca-default-locale")) {
            session::set(["error" => "Input error"]);
            return redierct()->back();
        }
        if (empty(Request::input("boca-default-locale"))):
            session::set(["error" => "Input error empty"]);
            return redierct()->back();
        endif;
        $value = wp_strip_all_tags(Request::input("boca-default-locale"));
        $update = update_option("boca_language_default", $value);
        if ($update) {
            session::set(["success" => "default locale " . $value . " Success"]);
        } else {
            session::set(["error" => "not set " . $value . " locale"]);
        }
        return redierct()->back();
    });
    Route::post("/add-translate", function () {
        if (empty(Request::input("_token_app")) || (session::get("_token_app") != Request::input("_token_app"))) {
            session::set(["error" => "error 401 Auth"]);
            return redierct()->back();
        }
        if (count(Request::body()) <= 1) {
            $update = update_option("boca_language_selected", []);
            if ($update) {
                session::set(["success" => "Create Success"]);
            } else {
                session::set(["error" => "error create"]);
            }
            return redierct()->back();
        }
        if (!Request::hasInput("language")) {
            session::set(["error" => "error input"]);
            return redierct()->back();
        }
        $language = array();
        if (empty(Request::input("language"))) {
            session::set(["error" => "Input empty"]);
            return redierct()->back();
        }
        foreach (Request::input("language") as $key => $value):
            $language[$key] = array(
                "name" => wp_strip_all_tags($key),
                "prefix" => wp_strip_all_tags($value["prefix"]),
                "active" => isset($value["active"]) ? "true" : "false",
                "code" => wp_strip_all_tags($value["code"]),
            );
        endforeach;
        $update = update_option("boca_language_selected", serialize($language));
        unset($language);
        if ($update) {
            session::set(["success" => "Create Success"]);
        } else {
            session::set(["error" => "error create"]);
        }
        return redierct()->back();
    });
    Route::post("/translate", function () {
        if (empty(Request::input("_token_app")) || (session::get("_token_app") != Request::input("_token_app"))) {
            session::set(["error" => "error 401 Auth"]);
            return redierct()->back();
        }

        if (!Request::hasInput("locale") || !Request::hasInput("translate_content")) {
            session::set(["error" => "error input"]);
            return redierct()->back();
        }
        if (empty(Request::input("locale")) || empty(Request::input("translate_content"))) {
            session::set(["error" => "error input empty"]);
            return redierct()->back();
        }
        $locale = wp_strip_all_tags(Request::input("locale"));
        $translate_content = wp_strip_all_tags(Request::input("translate_content"));
        if(!is_dir(app("dir_language"))){
            mkdir(app("dir_language"));
        }
        if(!is_dir(app("dir_language") . "/boca")){
            mkdir(app("dir_language") . "/boca");
        }
        if(!is_dir(app("dir_language") . "/boca/translate")){
            mkdir(app("dir_language") . "/boca/translate");
        }
        if(!is_dir(app("dir_language") . "/boca/$locale")){
            mkdir(app("dir_language") . "/boca/$locale");
        }
        if(!is_dir(app("dir_language") . "/boca/translate")){
            mkdir(app("dir_language") . "/boca/translate");
        }
        $locale_header_translate = app("dir_plugin") . "Languages/$locale-header.po";

        $template_header = "";
        $original = array();
        $replace = array();
        if (!file_exists($locale_header_translate)) {
            $header_file_translate = app("dir_plugin") . "Languages/po/header.po";
            try {
                $template_header = file_get_contents($header_file_translate);
            }catch (\Exception $e) {
                session::set(["error" => "An error occurred while opening the file"]);
                return redierct()->back();
            }


            $original[] = "Language:";
            $original[] = "POT-Creation-Date:";
            $original[] = "PO-Revision-Date:";

            $replace[] = "Language: " . $locale;
            $replace[] = "POT-Creation-Date: " . date("Y-m-d h:i:s");
            $replace[] = "PO-Revision-Date: " . date("Y-m-d h:i:s");
            $template_header = str_replace($original, $replace, $template_header);
        }else{
            try {
                $template_header = file_get_contents($locale_header_translate);
            }catch (\Exception $e) {
                session::set(["error" => "An error occurred while opening the file header"]);
                return redierct()->back();
            }
            $template_header =  preg_replace('/PO-Revision-Date:[\s\d\-:]+/' ,"PO-Revision-Date: " . date("Y-m-d h:i:s")  ,$template_header );
        }
        $finale_header = file_put_contents(app("dir_language") . "/boca/translate/$locale-header.po", $template_header);
        if(!$finale_header)
        {
            session::set(["error" => "An error occurred while writing to the file header translate"]);
            return redierct()->back();
        }
        try {
            $file_translate = file_put_contents(app("dir_language") . "/boca/translate/$locale-body.po",$translate_content);
        }catch (\Exception $e){
            session::set(["error" => "An error occurred while writing to the file body translate"]);
            return redierct()->back();
        }
        if(!$file_translate){
            session::set(["error" => "An error occurred while writing to the file body translate"]);
            return redierct()->back();
        }
        $template_header .= $translate_content;
        try {
            $finale_file_po = file_put_contents(app("dir_language") . "/boca/translate/$locale-translate.po" , $template_header);
        }catch (\Exception $e){
            session::set(["error" => "An error occurred while writing to the file"]);
            return redierct()->back();
        }
        if(!$finale_file_po){
            session::set(["error" => "An error occurred while writing to the file"]);
            return redierct()->back();
        }
        //import from a .po file:
        $loader = new PoLoader();
        $translations = $loader->loadFile(app("dir_language") . "/boca/translate/$locale-translate.po");
        //export to a .mo file:
        $generator = new MoGenerator();
        $generator->generateFile($translations, app("dir_language") . "/boca/$locale/translate.mo");
        session::set(["success" => "translate done"]);
        return redierct()->back();
    });
});
