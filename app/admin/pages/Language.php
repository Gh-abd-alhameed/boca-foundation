<?php

use boca\core\settings\Request;
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//
//
//}
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

<div class="container-fluid pt-5">
	<?php if(\boca\core\settings\session::has("success")): ?>
        <small class="alert alert-success"><?php echo \boca\core\settings\session::get("success"); \boca\core\settings\session::clear("success"); ?></small>
	<?php endif; ?>

	<?php if(\boca\core\settings\session::has("error")): ?>
        <small><?php echo \boca\core\settings\session::get("error"); \boca\core\settings\session::clear("error"); ?></small>
	<?php endif; ?>
    <div class="boca-tabs py-3">
        <div class="div-tabs d-flex gap-3" id="">
            <a data-target="main" data-active="true" class="tab-btn active">Language</a>
            <a data-target="translate" data-active="false" class="tab-btn">Translate</a>
        </div>
    </div>
    <div class="boca-tabs-content">
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
                                    if($value["active"] == "false"):
                                        continue;
                                    endif;
                                    ?>
                                    <option <?php echo app("locale") == $key ? "selected" : "" ?> value="<?php echo $key  ?>"><?php echo $key  ?></option>
								<?php endforeach;?>
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
                                        <input type="text"  disabled value="en_US"/>
                                    </div>
                                    <div class="col-6 d-flex  align-items-center">
                                        <label>Active</label>
                                        <input type="checkbox" checked value="1"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="">
                            <form class="d-flex flex-column gap-3" action="/wp-json/boca/v1/add-translate" method="POST">
                                <input type="text" name="_token_app" hidden
                                       value="<?php echo \boca\core\settings\session::get("_token_app") ?>"/>
                                <div class="row flex-column" id="boca-input-select-language">
                                    <?php foreach ($available_locales as $key => $value): ?>
                                        <div class="col-12">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="col-6 d-flex flex-column">
                                                    <label>Language</label>
                                                    <input type="text"  class=""  name="language[<?php echo $key ?>][name]" id=""
                                                           value="<?php echo $key ?>"/>
                                                </div>
                                                <div class="col-6 d-flex flex-column">
                                                    <label>prefix</label>
                                                    <input type="text"  name="language[<?php echo $key ?>][prefix]" value="<?php echo $value["prefix"] ?>"/>
                                                </div>
                                                <div class="col-6 d-flex flex-column">
                                                    <label>Code</label>
                                                    <input type="text"  name="language[<?php echo $key ?>][code]" value="<?php echo $value["code"] ?>"/>
                                                </div>
                                                <div class=" d-flex align-items-center">
                                                    <label>Active</label>
                                                    <input type="checkbox" class="" name="language[<?php echo $key ?>][active]"  <?php echo $value["active"] == "true" ? "checked" : "" ?> value="1"/>
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
                                                    <option value="fr">fr</option>
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
        <div class="content-tabs" data-toggle="translate" data-active="false">
            tab two
        </div>
    </div>
</div>

<script>
    let container_language = document.getElementById("boca-input-select-language");
    let btn_add_input_language = document.getElementById("boca_btn_add_language");
    let btn_remove_language = document.querySelectorAll(".boca-remove-items");
    btn_add_input_language.addEventListener("click", (e) => {
        let language = e.target.previousElementSibling.value;
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
                           <input type="text"  name="language[${language}][code]" value="${language}"/>
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

    btn_remove_language.forEach(el=>{
        el.addEventListener("click",(e)=>{
            e.target.parentElement.parentElement.remove();
        })
    })


</script>

