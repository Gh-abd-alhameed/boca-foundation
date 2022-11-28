<?php
defined("ABSPATH") or die('');


function if_slug_exists($slug){
    /*
     * if exists $slug return true else false
     * */
    global $wpdb;
    $post_if = $wpdb->get_var($wpdb->prepare("SELECT count(post_title) FROM $wpdb->posts WHERE post_name like '".$slug."'"));
    if($post_if){
        return true;
    }
    return false;
}
