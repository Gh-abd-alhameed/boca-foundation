<?php
defined("ABSPATH") or die('');

use boca\core\settings\Hooks;


$array = get_option("boca-metaboxes-fields");
$fields = $array ? unserialize($array) : [];

if (count($fields) > 0):
    Hooks::Init("add_meta_boxes", function () use ($fields) {
        Hooks::action(function () use ($fields) {
            foreach ($fields as $key => $value):
                add_meta_box($key, $value["name"], function ($post) use ($key, $value) {
                    wp_nonce_field('<?php echo $key . "_field_action" ?>', '<?php echo $key . "_field_name" ?>');
                    $output = "";
                    if ($value["type"] == "text"):
                        $value_option = get_post_meta($post->ID, $key, true);
                        $output = <<<HTML
                        <input type="text" id="{$key}_input_id" style="width:100%;" name="{$key}_input_name" value="$value_option" />
                        HTML;
                    endif;
                    if ($value["type"] == "image"):
                        $value_option = get_post_meta($post->ID, $key, true);
                        $image = $value_option ? wp_get_attachment_image_url($value_option) : app("url_plugin") . "public/assets/img/boca-img.png";
                        $output = <<<HTML
                            <div class="div-img-gallery">
                                <img loading="lazy" class="boca-show-image" src="$image" width="100%">
                            </div>
                            <div class="div-button-img-poster d-flex pt-3 gap-3">
                                <a  class="btn-outline-dark tab-btn boca-btn-add-image">add</a>
                                <a  class="btn-outline-danger tab-btn-delete boca-btn-delete-image">delete</a>
                            </div>
                            <input type="text" name="{$key}_input_name" id="{$key}_input_id" hidden
                                   value="$value_option"/>
                        HTML;
                    endif;
                    echo $output;
                }, $value["post_type"], $value["type"] == "image" ? 'side' : "advanced", 'default');
            endforeach;
        });
    });
    Hooks::Init("save_post", function () use ($fields) {
        Hooks::action(function ($post_id) use ($fields) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            foreach ($fields as $key => $value):
                if($value["type"] == "text"):
                update_post_meta($post_id, $key, sanitize_text_field($_POST["{$key}_input_name"]));
                endif;
                if($value["type"] == "image"):
                    update_post_meta($post_id, $key, absint($_POST["{$key}_input_name"]));
                endif;
            endforeach;
        });
    });
endif;

