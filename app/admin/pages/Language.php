<?php

use boca\core\settings\Request;

//use Gettext\Generator\PoGenerator;
//use Gettext\Translation;
//use Gettext\Translations;
//
//
//$translations = Translations::create("newdomain" , "ar");
//$translations->setDescription("languages ar team");
//$translation = Translation::create("Hello World" , "hello world");
//$translations->add($translation);
//$generatorPo = new PoGenerator();
//
//$file_po_generator = $generatorPo->generateFile($translations , app("dir_plugin") . "/Languages/domain.po");
//if($file_po_generator){
//    echo "generator file success";
//}
//die();
$languages = app("available_locales");
$available_locales = app("available_locales");
unset($available_locales["en"]);
?>
<style>
    * {
        font-size: 14px;
        font-family: system-ui;
    }

</style>

<div class="container-fluid pt-5 w-100">
	<?php if (\boca\core\settings\session::has("success")): ?>
        <small class="alert alert-success w-100"><?php echo \boca\core\settings\session::get("success");
			\boca\core\settings\session::clear("success"); ?></small>
	<?php endif; ?>

	<?php if (\boca\core\settings\session::has("error")): ?>
        <small class="alert alert-danger w-100"><?php echo \boca\core\settings\session::get("error");
			\boca\core\settings\session::clear("error"); ?></small>
	<?php endif; ?>
    <div class="boca-tabs py-3">
        <div class="div-tabs d-flex gap-3" id="">
            <a href="/wp-admin/admin.php?page=boca_submenu_Language" data-target="main" data-active="true"
               class="tab-btn active">Language</a>
            <a href="/wp-admin/admin.php?page=boca_submenu_Language&stringTranslation=true" data-target="main"
               data-active="true" class="tab-btn active">string translation</a>
            <a href="/wp-admin/admin.php?page=boca_submenu_Language&translate-language=true" data-target="translate"
               data-active="false" class="tab-btn">Translate</a>
            <a href="/wp-admin/admin.php?page=boca_submenu_Language&translate-domain=true" data-target="translate"
               data-active="false" class="tab-btn">Domain</a>
        </div>
    </div>
    <div class="boca-tabs-content">
		<?php if (Request::hasInput("stringTranslation")): ?>
            <form action="/wp-json/boca/v1/add-translate-string" method="POST">
                <input  type="text" value="" name="string" />
                <input  type="text" value="" name="translate" />
                <input type="submit"  value="save" class="btn btn-outline-dark" />
            </form>
		<?php endif; ?>
		<?php if (Request::uri() == "/wp-admin/admin.php?page=boca_submenu_Language"): ?>
            <div class="content-tabs active" data-toggle="main" data-active="true">
                <table class="d-flex py-4 gap-5">
                    <thead class="">
                    <tr class="d-flex flex-column gap-4">
                        <th>
                            DefaultLanguage
                        </th>
                        <th>
                            manage Language
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="d-flex flex-column gap-4">
                        <td>
                            <form class="row" action="/wp-json/boca/v1/add-locale-default" method="POST">
                                <input type="text" name="_token_app" hidden
                                       value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                                <select class="form-select" name="boca-default-locale" id="" value="">
									<?php foreach ($languages as $key => $value):
										if ($value["active"] == "false"):
											continue;
										endif;
										?>
                                        <option <?php echo app("locale") == $key ? "selected" : "" ?>
                                                value="<?php echo $key ?>"><?php echo $key ?></option>
									<?php endforeach; ?>
                                </select>
                                <input type="submit" class="btn btn-outline-dark" style="width: fit-content;"
                                       value="save"/>
                            </form>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="col-6 d-flex flex-column">
                                            <label>Language</label>
                                            <input type="text" disabled value="en"/>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <label>prefix</label>
                                            <input type="text" disabled value="/"/>
                                        </div>
                                        <div class="col-6 d-flex flex-column">
                                            <label>Code</label>
                                            <input type="text" disabled value="en_US"/>
                                        </div>
                                        <div class="col-6 d-flex  align-items-center">
                                            <label>Active</label>
                                            <input type="checkbox" checked value="1"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="">
                                <form class="d-flex flex-column gap-3" action="/wp-json/boca/v1/add-translate"
                                      method="POST">
                                    <input type="text" name="_token_app" hidden
                                           value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                                    <div class="row flex-column" id="boca-input-select-language">
										<?php foreach ($available_locales as $key => $value): ?>
                                            <div class="col-12">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="col-6 d-flex flex-column">
                                                        <label>Language</label>
                                                        <input type="text" class=""
                                                               name="language[<?php echo $key ?>][name]" id=""
                                                               value="<?php echo $key ?>"/>
                                                    </div>
                                                    <div class="col-6 d-flex flex-column">
                                                        <label>prefix</label>
                                                        <input type="text" name="language[<?php echo $key ?>][prefix]"
                                                               value="<?php echo $value["prefix"] ?>"/>
                                                    </div>
                                                    <div class="col-6 d-flex flex-column">
                                                        <label>Code</label>
                                                        <input type="text" name="language[<?php echo $key ?>][code]"
                                                               value="<?php echo $value["code"] ?>"/>
                                                    </div>
                                                    <div class=" d-flex align-items-center">
                                                        <label>Active</label>
                                                        <input type="checkbox" class=""
                                                               name="language[<?php echo $key ?>][active]" <?php echo $value["active"] == "true" ? "checked" : "" ?>
                                                               value="1"/>
                                                    </div>
                                                    <a class="btn btn-danger boca-remove-items" style="">-</a>
                                                </div>

                                            </div>
										<?php endforeach; ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex flex-column gap-2">
                                                <label>Language</label>
                                                <div class="col-6 d-flex align-items-center gap-3">
                                                    <select class="form-select">
                                                        <option value="ar">ar</option>
                                                        <option value="fr,fr_FR">fr</option>
                                                    </select>
                                                    <a class="btn btn-dark" id="boca_btn_add_language">+</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-outline-dark" style="width: fit-content;"
                                           value="save"/>
                                </form>

                            </div>


                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
		<?php endif; ?>
		<?php if (Request::hasInput("translate-language") && (Request::input("translate-language") == "true")): ?>
            <div class="content-tabs" data-toggle="translate" data-active="false">
                <div class="row d-flex align-items-center justify-content-center py-3">
                    <div class="d-flex align-items-center">
                        <label>select language</label>
                        <select name="language" id="boca-select-language-translate" style="min-width:200px;">
                            <option value="">Select Language</option>
							<?php foreach (app("available_locales") as $key => $value): ?>
                                <option
									<?php
									echo Request::hasInput("language") && (Request::input("language") == $key) ? "selected" : "" ?>
                                        value="<?php echo "/wp-admin/admin.php?page=boca_submenu_Language&translate-language=true&" . _http_build_query(array("language" => $key)); ?>"><?php echo $key ?></option>
							<?php endforeach; ?>
                        </select>
                    </div>

                </div>
				<?php if (Request::hasInput("language")): ?>
                    <form action="/wp-json/boca/v1/translate" method="POST">
                        <input type="text" name="_token_app" hidden
                               value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                        <div class="row">
                            <div class="col-12 py-3">
                                <div class="row gap-3">
                                    <div class="d-flex gap-3 align-items-center">
                                        <label>Locale: <?php echo Request::input("language") ?></label>
                                        <input type="text" name="locale" hidden
                                               value="<?php echo Request::input("language") ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
								<?php
								$locale = wp_strip_all_tags(Request::input("language"));
								$path = app("dir_language") . "/boca/translate/$locale-body.po";
								if (!file_exists($path)) {
									$path = app("dir_plugin") . "Languages/po/default.po";
								}
								$file = file_get_contents($path);
								?>
                                <textarea class="w-100 bg-dark" name="translate_content"
                                          style="min-height:400px;color:white;">
                                <?php echo $file ?>
                            </textarea>
                                <input type="submit" class="btn btn-outline-dark" value="save"/>
                            </div>
                        </div>
                    </form>
				<?php endif; ?>
            </div>
		<?php endif; ?>
		<?php if (Request::hasInput("translate-domain") && (Request::input("translate-domain") == "true")): ?>
            <div class="content-tabs" data-toggle="translate" data-active="false">
                <div class="row d-flex align-items-center justify-content-center py-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3">
                            <label>Create Domain: </label>
                            <input type="text" name="text-domain" value=""/>
                        </div>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
</div>

<script>
    let container_language = document.getElementById("boca-input-select-language");
    let btn_add_input_language = document.getElementById("boca_btn_add_language");
    let btn_remove_language = document.querySelectorAll(".boca-remove-items");
    if (btn_add_input_language != null) {
        btn_add_input_language.addEventListener("click", (e) => {
            let language = e.target.previousElementSibling.value;
            let pattern = /\w+,[\w_]+$/;
            let language_code = language;
            if (pattern.test(language)) {
                let convert = language.split(",");
                language = convert[0];
                language_code = convert[1];
            }
            let div = document.createElement("div");
            div.classList.add("col-12");

            let component_input_language = `

                <div class="d-flex gap-2 align-items-center">
                    <div class="col-6 d-flex flex-column">
                        <label>Language</label>
                        <input type="text"  class=""  name="language[${language}][name]" id=""
                               value="${language}"/>
                    </div>
                    <div class="col-6 d-flex flex-column">
                        <label>prefix</label>
                        <input type="text"  name="language[${language}][prefix]" value="/${language}"/>
                    </div>
                       <div class="col-6 d-flex flex-column">
                           <label>Code</label>
                           <input type="text"  name="language[${language}][code]" value="${language_code}"/>
                       </div>
                    <div class="col-6 d-flex align-items-center">
                        <label>Active</label>
                        <input type="checkbox" class="" name="language[${language}][active]" value="1"/>
                    </div>
                    <a class="btn btn-danger boca-remove-items" style="">-</a>
                </div>
    `;
            div.innerHTML = component_input_language;
            container_language.appendChild(div);
        })

        btn_remove_language.forEach(el => {
            el.addEventListener("click", (e) => {
                e.target.parentElement.parentElement.remove();
            })
        })
    }
    let btn_select_language_translate = document.getElementById("boca-select-language-translate");
    if (btn_select_language_translate != null) {
        btn_select_language_translate.addEventListener("change", (e) => {
            location.href = e.target.value;
        })
    }
</script>



