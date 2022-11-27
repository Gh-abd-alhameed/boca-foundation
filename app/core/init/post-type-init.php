<?php
defined("ABSPATH") or die('');
use boca\core\settings\Hooks;

array(
	"news"=>[
		"labels"=>[],
		"args"=>[]
	]
);


$labels = get_option("boca-posts-types");


Hooks::Init("init",function (){
	Hooks::action(function (){

	});
});

function maxart_post_type_news()
{
	$labels = array(
		'name' => 'News',
		'singular_name' => 'News',
		'menu_name' => 'News',
		'name_admin_bar' => 'News',
		'add_new' => 'Add New News',
		'add_new_item' => 'Add New News',
		'new_item' => 'New News',
		'edit_item' => 'Edit News',
		'view_item' => 'View News',
		'all_items' => 'All News',
		'search_items' => 'Search News',
		'parent_item_colon' => 'Parent News:',
		'not_found' => 'No News found.',
		'not_found_in_trash' => 'No News found in Trash.',
		'featured_image' => 'News Cover Image',
		'set_featured_image' => 'Set cover image',
		'remove_featured_image' => 'Remove cover image',
		'use_featured_image' => 'Use as cover image',
		'archives' => 'News archives',
		'insert_into_item' => 'Insert into News',
		'uploaded_to_this_item' => 'Uploaded to this News',
		'filter_items_list' => 'Filter News list',
		'items_list_navigation' => 'News list navigation',
		'items_list' => 'News list',
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'News', 'with_front' => false),
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => true,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
		'show_in_rest' => true,
		'exclude_from_search' => false,
		'menu_icon' => 'dashicons-edit-page',
	);

	register_post_type('news', $args);
}

add_action('init', 'maxart_post_type_news');