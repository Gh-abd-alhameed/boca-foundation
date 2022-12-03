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
                    $value_option = get_post_meta($post->ID, $key, true);
                    $output = "";
                    if ($value["type"] == "text"):
                        $output = <<<HTML
                        <input type="text" id="{$key}_input_id" style="width:100%;" name="{$key}_input_name" value="$value_option" />
                        HTML;
                    endif;
                    echo $output;
                }, $value["post_type"], 'advanced', 'default');
            endforeach;
        });
    });
    Hooks::Init("save_post", function () use ($fields) {
        Hooks::action(function ($post_id) use ($fields) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            foreach ($fields as $key => $value):
                update_post_meta($post_id, $key, sanitize_text_field($_POST["{$key}_input_name"]));
            endforeach;
        });
    });
endif;

