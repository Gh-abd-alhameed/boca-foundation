<?php
defined( "ABSPATH" ) or die( '' );

use boca\core\settings\Request;
use boca\core\settings\Route;
use boca\core\settings\session;
use Gettext\Loader\PoLoader;
use Gettext\Generator\MoGenerator;

Route::Init( "/boca/v1", function () {
	Route::post( "/add-locale-default", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "boca-default-locale" ) ) {
			session::set( [ "error" => "Input error" ] );

			return redierct()->back();
		}
		if ( empty( Request::input( "boca-default-locale" ) ) ):
			session::set( [ "error" => "Input error empty" ] );

			return redierct()->back();
		endif;
		$value  = wp_strip_all_tags( Request::input( "boca-default-locale" ) );
		$update = update_option( "boca_language_default", $value );
		if ( $update ) {
			session::set( [ "success" => "default locale " . $value . " Success" ] );
		} else {
			session::set( [ "error" => "not set " . $value . " locale" ] );
		}

		return redierct()->back();
	} );
	Route::post( "/add-translate", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( count( Request::body() ) <= 1 ) {
			$update = update_option( "boca_language_selected", [] );
			if ( $update ) {
				session::set( [ "success" => "Create Success" ] );
			} else {
				session::set( [ "error" => "error create" ] );
			}

			return redierct()->back();
		}
		if ( ! Request::hasInput( "language" ) ) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		$language = array();
		if ( empty( Request::input( "language" ) ) ) {
			session::set( [ "error" => "Input empty" ] );

			return redierct()->back();
		}
		foreach ( Request::input( "language" ) as $key => $value ):
			$language[ $key ] = array(
				"name"   => wp_strip_all_tags( $key ),
				"prefix" => wp_strip_all_tags( $value["prefix"] ),
				"active" => isset( $value["active"] ) ? "true" : "false",
				"code"   => wp_strip_all_tags( $value["code"] ),
			);
		endforeach;
		$update = update_option( "boca_language_selected", serialize( $language ) );
		unset( $language );
		if ( $update ) {
			session::set( [ "success" => "Create Success" ] );
		} else {
			session::set( [ "error" => "error create" ] );
		}

		return redierct()->back();
	} );
	Route::post( "/translate", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}

		if ( ! Request::hasInput( "locale" ) || ! Request::hasInput( "translate_content" ) ) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		if ( empty( Request::input( "locale" ) ) || empty( Request::input( "translate_content" ) ) ) {
			session::set( [ "error" => "error input empty" ] );

			return redierct()->back();
		}
		$locale            = wp_strip_all_tags( Request::input( "locale" ) );
		$translate_content = wp_strip_all_tags( Request::input( "translate_content" ) );
		if ( ! is_dir( app( "dir_language" ) ) ) {
			mkdir( app( "dir_language" ) );
		}
		if ( ! is_dir( app( "dir_language" ) . "/boca" ) ) {
			mkdir( app( "dir_language" ) . "/boca" );
		}
		if ( ! is_dir( app( "dir_language" ) . "/boca/translate" ) ) {
			mkdir( app( "dir_language" ) . "/boca/translate" );
		}
		if ( ! is_dir( app( "dir_language" ) . "/boca/$locale" ) ) {
			mkdir( app( "dir_language" ) . "/boca/$locale" );
		}
		if ( ! is_dir( app( "dir_language" ) . "/boca/translate" ) ) {
			mkdir( app( "dir_language" ) . "/boca/translate" );
		}
		$locale_header_translate = app( "dir_plugin" ) . "Languages/$locale-header.po";
		$template_header         = "";
		$original                = array();
		$replace                 = array();
		if ( ! file_exists( $locale_header_translate ) ) {
			$header_file_translate = app( "dir_plugin" ) . "Languages/po/header.po";
			try {
				$template_header = file_get_contents( $header_file_translate );
			} catch ( \Exception $e ) {
				session::set( [ "error" => "An error occurred while opening the file" ] );

				return redierct()->back();
			}
			$original[] = "Language:";
			$original[] = "POT-Creation-Date:";
			$original[] = "PO-Revision-Date:";

			$replace[]       = "Language: " . $locale;
			$replace[]       = "POT-Creation-Date: " . date( "Y-m-d h:i:s" );
			$replace[]       = "PO-Revision-Date: " . date( "Y-m-d h:i:s" );
			$template_header = str_replace( $original, $replace, $template_header );
		} else {
			try {
				$template_header = file_get_contents( $locale_header_translate );
			} catch ( \Exception $e ) {
				session::set( [ "error" => "An error occurred while opening the file header" ] );

				return redierct()->back();
			}
			$template_header = preg_replace( '/PO-Revision-Date:[\s\d\-:]+/', "PO-Revision-Date: " . date( "Y-m-d h:i:s" ), $template_header );
		}
		$finale_header = file_put_contents( app( "dir_language" ) . "/boca/translate/$locale-header.po", $template_header );
		if ( ! $finale_header ) {
			session::set( [ "error" => "An error occurred while writing to the file header translate" ] );

			return redierct()->back();
		}
		try {
			$file_translate = file_put_contents( app( "dir_language" ) . "/boca/translate/$locale-body.po", $translate_content );
		} catch ( \Exception $e ) {
			session::set( [ "error" => "An error occurred while writing to the file body translate" ] );

			return redierct()->back();
		}
		if ( ! $file_translate ) {
			session::set( [ "error" => "An error occurred while writing to the file body translate" ] );

			return redierct()->back();
		}
		$template_header .= $translate_content;
		try {
			$finale_file_po = file_put_contents( app( "dir_language" ) . "/boca/translate/$locale-translate.po", $template_header );
		} catch ( \Exception $e ) {
			session::set( [ "error" => "An error occurred while writing to the file" ] );

			return redierct()->back();
		}
		if ( ! $finale_file_po ) {
			session::set( [ "error" => "An error occurred while writing to the file" ] );

			return redierct()->back();
		}
		//import from a .po file:
		$loader       = new PoLoader();
		$translations = $loader->loadFile( app( "dir_language" ) . "/boca/translate/$locale-translate.po" );
		//export to a .mo file:
		$generator = new MoGenerator();
		$generator->generateFile( $translations, app( "dir_language" ) . "/boca/$locale/translate.mo" );
		session::set( [ "success" => "translate done" ] );

		return redierct()->back();
	} );
	// Add Post Type
	Route::post( "/delete-post-type", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "post-type" ) || empty( Request::input( "post-type" ) ) ) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		$post_name   = wp_strip_all_tags( Request::input( "post-type" ) );
		$array       = get_option( "boca-posts-types" );
		$array_posts = $array ? unserialize( $array ) : [];
		if ( count( $array_posts ) <= 0 ) {
			session::set( [ "error" => "There are no data" ] );

			return redierct()->back();
		}
		if ( ! key_exists( $post_name, $array_posts ) ) {
			session::set( [ "error" => "post type does not exist" ] );

			return redierct()->back();
		}
		unset( $array_posts[ $post_name ] );
		$new_post_types = update_option( "boca-posts-types", serialize( $array_posts ) );
		if ( ! $new_post_types ) {
			session::set( [ "error" => "An unexpected error occurred while deleting. Check the connection" ] );

			return redierct()->back();
		}
		session::set( [ "success" => "Deleted successfully" ] );

		return redierct()->back();
	} );
	Route::post( "/edit-post-type", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if (
			! Request::hasInput( "name_post_type" ) ||
			! Request::hasInput( "name" ) ||
			! Request::hasInput( "singular_name" ) ||
			! Request::hasInput( "menu_name" ) ||
			! Request::hasInput( "add_new" ) ||
			! Request::hasInput( "name_admin_bar" ) ||
			! Request::hasInput( "add_new_item" ) ||
			! Request::hasInput( "new_item" ) ||
			! Request::hasInput( "edit_item" ) ||
			! Request::hasInput( "view_item" ) ||
			! Request::hasInput( "all_items" ) ||
			! Request::hasInput( "search_items" ) ||
			! Request::hasInput( "parent_item_colon" ) ||
			! Request::hasInput( "not_found" ) ||
			! Request::hasInput( "not_found_in_trash" ) ||
			! Request::hasInput( "featured_image" ) ||
			! Request::hasInput( "set_featured_image" ) ||
			! Request::hasInput( "remove_featured_image" ) ||
			! Request::hasInput( "use_featured_image" ) ||
			! Request::hasInput( "archives" ) ||
			! Request::hasInput( "insert_into_item" ) ||
			! Request::hasInput( "uploaded_to_this_item" ) ||
			! Request::hasInput( "filter_items_list" ) ||
			! Request::hasInput( "items_list_navigation" ) ||
			! Request::hasInput( "items_list" ) ) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		if (
			empty( Request::input( "name_post_type" ) ) ||
			empty( Request::input( "name" ) ) ||
			empty( Request::input( "singular_name" ) ) ||
			empty( Request::input( "menu_name" ) ) ||
			empty( Request::input( "add_new" ) ) ||
			empty( Request::input( "new_item" ) ) ||
			empty( Request::input( "add_new_item" ) ) ||
			empty( Request::input( "name_admin_bar" ) ) ||
			empty( Request::input( "edit_item" ) ) ||
			empty( Request::input( "view_item" ) ) ||
			empty( Request::input( "all_items" ) ) ||
			empty( Request::input( "search_items" ) ) ||
			empty( Request::input( "parent_item_colon" ) ) ||
			empty( Request::input( "not_found" ) ) ||
			empty( Request::input( "not_found_in_trash" ) ) ||
			empty( Request::input( "featured_image" ) ) ||
			empty( Request::input( "set_featured_image" ) ) ||
			empty( Request::input( "remove_featured_image" ) ) ||
			empty( Request::input( "use_featured_image" ) ) ||
			empty( Request::input( "archives" ) ) ||
			empty( Request::input( "insert_into_item" ) ) ||
			empty( Request::input( "uploaded_to_this_item" ) ) ||
			empty( Request::input( "filter_items_list" ) ) ||
			empty( Request::input( "items_list_navigation" ) ) ||
			empty( Request::input( "items_list" ) )
		) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "rewrite" ) || empty( Request::input( "rewrite" ) ) ) {
			session::set( [ "error" => "error rewrite rule" ] );

			return redierct()->back();
		}
		$name_post_type        = strtolower( wp_strip_all_tags( Request::input( "name_post_type" ) ) );
		$name                  = wp_strip_all_tags( Request::input( "name" ) );
		$singular_name         = wp_strip_all_tags( Request::input( "singular_name" ) );
		$menu_name             = wp_strip_all_tags( Request::input( "menu_name" ) );
		$name_admin_bar        = wp_strip_all_tags( Request::input( "name_admin_bar" ) );
		$add_new               = wp_strip_all_tags( Request::input( "add_new" ) );
		$add_new_item          = wp_strip_all_tags( Request::input( "add_new_item" ) );
		$new_item              = wp_strip_all_tags( Request::input( "new_item" ) );
		$edit_item             = wp_strip_all_tags( Request::input( "edit_item" ) );
		$view_item             = wp_strip_all_tags( Request::input( "view_item" ) );
		$all_items             = wp_strip_all_tags( Request::input( "all_items" ) );
		$search_items          = wp_strip_all_tags( Request::input( "search_items" ) );
		$parent_item_colon     = wp_strip_all_tags( Request::input( "parent_item_colon" ) );
		$not_found             = wp_strip_all_tags( Request::input( "not_found" ) );
		$not_found_in_trash    = wp_strip_all_tags( Request::input( "not_found_in_trash" ) );
		$featured_image        = wp_strip_all_tags( Request::input( "featured_image" ) );
		$set_featured_image    = wp_strip_all_tags( Request::input( "set_featured_image" ) );
		$remove_featured_image = wp_strip_all_tags( Request::input( "remove_featured_image" ) );
		$use_featured_image    = wp_strip_all_tags( Request::input( "use_featured_image" ) );
		$archives              = wp_strip_all_tags( Request::input( "archives" ) );
		$insert_into_item      = wp_strip_all_tags( Request::input( "insert_into_item" ) );
		$uploaded_to_this_item = wp_strip_all_tags( Request::input( "uploaded_to_this_item" ) );
		$filter_items_list     = wp_strip_all_tags( Request::input( "filter_items_list" ) );
		$items_list_navigation = wp_strip_all_tags( Request::input( "items_list_navigation" ) );
		$items_list            = wp_strip_all_tags( Request::input( "items_list" ) );
		$rewrite               = wp_strip_all_tags( Request::input( 'rewrite' ) );
		$posts                 = get_option( "boca-posts-types" );
		$post_type_rejester    = $posts ? unserialize( $posts ) : [];
		if ( ! key_exists( $name_post_type, $post_type_rejester ) || ! key_exists( $name_post_type, get_post_types() ) ) {
			session::set( [ "error" => "Post Type not exists" ] );

			return redierct()->back();
		}
		$post_type_rejester[ $name_post_type ] = [
			'labels'              => array(
				'name'                  => __( $name, "boca-domain" ),
				'singular_name'         => __( $singular_name, "boca-domain" ),
				'menu_name'             => __( $menu_name, "boca-domain" ),
				'name_admin_bar'        => __( $name_admin_bar, "boca-domain" ),
				'add_new'               => __( $add_new, "boca-domain" ),
				'add_new_item'          => __( $add_new_item, "boca-domain" ),
				'new_item'              => __( $new_item, "boca-domain" ),
				'edit_item'             => __( $edit_item, "boca-domain" ),
				'view_item'             => __( $view_item, "boca-domain" ),
				'all_items'             => __( $all_items, "boca-domain" ),
				'search_items'          => __( $search_items, "boca-domain" ),
				'parent_item_colon'     => __( $parent_item_colon, "boca-domain" ),
				'not_found'             => __( $not_found, "boca-domain" ),
				'not_found_in_trash'    => __( $not_found_in_trash, "boca-domain" ),
				'featured_image'        => __( $featured_image, "boca-domain" ),
				'set_featured_image'    => __( $set_featured_image, "boca-domain" ),
				'remove_featured_image' => __( $remove_featured_image, "boca-domain" ),
				'use_featured_image'    => __( $use_featured_image, "boca-domain" ),
				'archives'              => __( $archives, "boca-domain" ),
				'insert_into_item'      => __( $insert_into_item, "boca-domain" ),
				'uploaded_to_this_item' => __( $uploaded_to_this_item, "boca-domain" ),
				'filter_items_list'     => __( $filter_items_list, "boca-domain" ),
				'items_list_navigation' => __( $items_list_navigation, "boca-domain" ),
				'items_list'            => __( $items_list, "boca-domain" ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => $rewrite, 'with_front' => false ),
			'capability_type'     => 'post',
			'has_archive'         => true,
			'hierarchical'        => true,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'        => false,
			'exclude_from_search' => false,
			'menu_icon'           => 'dashicons-edit-page',
		];
		$add_post_type                         = update_option( "boca-posts-types", serialize( (array) $post_type_rejester ) );
		if ( ! $add_post_type ) {
			session::set( [ "error" => "An unexpected error occurred at the entry Check the connection" ] );

			return redierct()->back();
		}
		session::set( [ "success" => "Added successfully" ] );

		return redierct()->back();


	} );
	Route::post( "/flush-rewrite", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		flush_rewrite_rules();
		session::set( [ "success" => "rewrite rule done" ] );

		return redierct()->back();
	} );
	Route::post( "/add-post-type", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if (
			! Request::hasInput( "name_post_type" ) ||
			! Request::hasInput( "name" ) ||
			! Request::hasInput( "singular_name" ) ||
			! Request::hasInput( "menu_name" ) ||
			! Request::hasInput( "add_new" ) ||
			! Request::hasInput( "name_admin_bar" ) ||
			! Request::hasInput( "add_new_item" ) ||
			! Request::hasInput( "new_item" ) ||
			! Request::hasInput( "edit_item" ) ||
			! Request::hasInput( "view_item" ) ||
			! Request::hasInput( "all_items" ) ||
			! Request::hasInput( "search_items" ) ||
			! Request::hasInput( "parent_item_colon" ) ||
			! Request::hasInput( "not_found" ) ||
			! Request::hasInput( "not_found_in_trash" ) ||
			! Request::hasInput( "featured_image" ) ||
			! Request::hasInput( "set_featured_image" ) ||
			! Request::hasInput( "remove_featured_image" ) ||
			! Request::hasInput( "use_featured_image" ) ||
			! Request::hasInput( "archives" ) ||
			! Request::hasInput( "insert_into_item" ) ||
			! Request::hasInput( "uploaded_to_this_item" ) ||
			! Request::hasInput( "filter_items_list" ) ||
			! Request::hasInput( "items_list_navigation" ) ||
			! Request::hasInput( "items_list" ) ) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		if (
			empty( Request::input( "name_post_type" ) ) ||
			empty( Request::input( "name" ) ) ||
			empty( Request::input( "singular_name" ) ) ||
			empty( Request::input( "menu_name" ) ) ||
			empty( Request::input( "add_new" ) ) ||
			empty( Request::input( "new_item" ) ) ||
			empty( Request::input( "add_new_item" ) ) ||
			empty( Request::input( "name_admin_bar" ) ) ||
			empty( Request::input( "edit_item" ) ) ||
			empty( Request::input( "view_item" ) ) ||
			empty( Request::input( "all_items" ) ) ||
			empty( Request::input( "search_items" ) ) ||
			empty( Request::input( "parent_item_colon" ) ) ||
			empty( Request::input( "not_found" ) ) ||
			empty( Request::input( "not_found_in_trash" ) ) ||
			empty( Request::input( "featured_image" ) ) ||
			empty( Request::input( "set_featured_image" ) ) ||
			empty( Request::input( "remove_featured_image" ) ) ||
			empty( Request::input( "use_featured_image" ) ) ||
			empty( Request::input( "archives" ) ) ||
			empty( Request::input( "insert_into_item" ) ) ||
			empty( Request::input( "uploaded_to_this_item" ) ) ||
			empty( Request::input( "filter_items_list" ) ) ||
			empty( Request::input( "items_list_navigation" ) ) ||
			empty( Request::input( "items_list" ) )
		) {
			session::set( [ "error" => "error input" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "rewrite" ) || empty( Request::input( "rewrite" ) ) ) {
			session::set( [ "error" => "error rewrite rule" ] );

			return redierct()->back();
		}
		$name_post_type        = strtolower( wp_strip_all_tags( Request::input( "name_post_type" ) ) );
		$name                  = wp_strip_all_tags( Request::input( "name" ) );
		$singular_name         = wp_strip_all_tags( Request::input( "singular_name" ) );
		$menu_name             = wp_strip_all_tags( Request::input( "menu_name" ) );
		$name_admin_bar        = wp_strip_all_tags( Request::input( "name_admin_bar" ) );
		$add_new               = wp_strip_all_tags( Request::input( "add_new" ) );
		$add_new_item          = wp_strip_all_tags( Request::input( "add_new_item" ) );
		$new_item              = wp_strip_all_tags( Request::input( "new_item" ) );
		$edit_item             = wp_strip_all_tags( Request::input( "edit_item" ) );
		$view_item             = wp_strip_all_tags( Request::input( "view_item" ) );
		$all_items             = wp_strip_all_tags( Request::input( "all_items" ) );
		$search_items          = wp_strip_all_tags( Request::input( "search_items" ) );
		$parent_item_colon     = wp_strip_all_tags( Request::input( "parent_item_colon" ) );
		$not_found             = wp_strip_all_tags( Request::input( "not_found" ) );
		$not_found_in_trash    = wp_strip_all_tags( Request::input( "not_found_in_trash" ) );
		$featured_image        = wp_strip_all_tags( Request::input( "featured_image" ) );
		$set_featured_image    = wp_strip_all_tags( Request::input( "set_featured_image" ) );
		$remove_featured_image = wp_strip_all_tags( Request::input( "remove_featured_image" ) );
		$use_featured_image    = wp_strip_all_tags( Request::input( "use_featured_image" ) );
		$archives              = wp_strip_all_tags( Request::input( "archives" ) );
		$insert_into_item      = wp_strip_all_tags( Request::input( "insert_into_item" ) );
		$uploaded_to_this_item = wp_strip_all_tags( Request::input( "uploaded_to_this_item" ) );
		$filter_items_list     = wp_strip_all_tags( Request::input( "filter_items_list" ) );
		$items_list_navigation = wp_strip_all_tags( Request::input( "items_list_navigation" ) );
		$items_list            = wp_strip_all_tags( Request::input( "items_list" ) );
		$rewrite               = wp_strip_all_tags( Request::input( 'rewrite' ) );
		$posts                 = get_option( "boca-posts-types" );
		$post_type_rejester    = $posts ? unserialize( $posts ) : [];
		if ( key_exists( $name_post_type, $post_type_rejester ) || key_exists( $name_post_type, get_post_types() ) ) {
			session::set( [ "error" => "Post name used" ] );

			return redierct()->back();
		}
		$post_type_rejester[ $name_post_type ] = [
			'labels'              => array(
				'name'                  => __( $name, "boca-domain" ),
				'singular_name'         => __( $singular_name, "boca-domain" ),
				'menu_name'             => __( $menu_name, "boca-domain" ),
				'name_admin_bar'        => __( $name_admin_bar, "boca-domain" ),
				'add_new'               => __( $add_new, "boca-domain" ),
				'add_new_item'          => __( $add_new_item, "boca-domain" ),
				'new_item'              => __( $new_item, "boca-domain" ),
				'edit_item'             => __( $edit_item, "boca-domain" ),
				'view_item'             => __( $view_item, "boca-domain" ),
				'all_items'             => __( $all_items, "boca-domain" ),
				'search_items'          => __( $search_items, "boca-domain" ),
				'parent_item_colon'     => __( $parent_item_colon, "boca-domain" ),
				'not_found'             => __( $not_found, "boca-domain" ),
				'not_found_in_trash'    => __( $not_found_in_trash, "boca-domain" ),
				'featured_image'        => __( $featured_image, "boca-domain" ),
				'set_featured_image'    => __( $set_featured_image, "boca-domain" ),
				'remove_featured_image' => __( $remove_featured_image, "boca-domain" ),
				'use_featured_image'    => __( $use_featured_image, "boca-domain" ),
				'archives'              => __( $archives, "boca-domain" ),
				'insert_into_item'      => __( $insert_into_item, "boca-domain" ),
				'uploaded_to_this_item' => __( $uploaded_to_this_item, "boca-domain" ),
				'filter_items_list'     => __( $filter_items_list, "boca-domain" ),
				'items_list_navigation' => __( $items_list_navigation, "boca-domain" ),
				'items_list'            => __( $items_list, "boca-domain" ),
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'query_var'           => true,
			'rewrite'             => array( 'slug' => $rewrite, 'with_front' => false ),
			'capability_type'     => 'post',
			'has_archive'         => true,
			'hierarchical'        => true,
			'menu_position'       => null,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'        => false,
			'exclude_from_search' => false,
			'menu_icon'           => 'dashicons-edit-page',
		];
		$add_post_type                         = update_option( "boca-posts-types", serialize( (array) $post_type_rejester ) );
		if ( ! $add_post_type ) {
			session::set( [ "error" => "An unexpected error occurred at the entry Check the connection" ] );

			return redierct()->back();
		}
		session::set( [ "success" => "Added successfully" ] );

		return redierct()->back();
	} );
	Route::post( "/add-translate-string", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}

		return null;
	} );
	Route::post( "/create-rewrite-rule", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "url" ) || ! Request::hasInput( "matches" ) || ! Request::hasInput( "rule" ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		if ( ! is_array( Request::input( "url" ) ) || ! is_array( Request::input( "matches" ) ) || ! is_array( Request::input( "rule" ) ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		$rewrite_rule = array();
		$url          = Request::input( "url" );
		$matches      = Request::input( "matches" );
		$rule         = Request::input( "rule" );
		foreach ( $url as $key => $value ) {
			$rewrite_rule[] = array(
				"url"     => wp_strip_all_tags( $value ),
				"matches" => wp_strip_all_tags( $matches[ $key ] ),
				"rule"    => wp_strip_all_tags( $rule[ $key ] )
			);
		}
		$rewrite_add = update_option( "boca-rewrite-rule", serialize( $rewrite_rule ) );
		session::set( [ "success" => "success" ] );

		return redierct()->back();
	} );
	Route::post( "/create-redirect", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "oldUrl" ) || ! Request::hasInput( "newUrl" ) || ! Request::hasInput( "code" ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		if ( ! is_array( Request::input( "oldUrl" ) ) || ! is_array( Request::input( "newUrl" ) ) || ! is_array( Request::input( "code" ) ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}

		$old_url  = Request::input( "oldUrl" );
		$new_url  = Request::input( "newUrl" );
		$code     = Request::input( "code" );
		$redirect = array();
		foreach ( $old_url as $key => $value ) {
			$redirect[] = array(
				"old"  => wp_strip_all_tags( $value ),
				"new"  => wp_strip_all_tags( $new_url[ $key ] ),
				"code" => abs( (int) $code[ $key ] )
			);
		}
		$store = update_option( "boca-redirect", serialize( $redirect ) );
		if ( ! $store ) {
			session::set( [ "success" => "save success" ] );

			return redierct()->back();
		}
		session::set( [ "success" => "success" ] );

		return redierct()->back();
	} );
	Route::post( "/create-rewrite-tag", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "tag" ) || ! Request::hasInput( "regx" ) || ! Request::hasInput( "query" ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		if ( ! is_array( Request::input( "tag" ) ) || ! is_array( Request::input( "regx" ) ) || ! is_array( Request::input( "query" ) ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		$tag         = Request::input( "tag" );
		$regx        = Request::input( "regx" );
		$query       = Request::input( "query" );
		$rewrite_tag = array();
		foreach ( $tag as $key => $value ) {
			$rewrite_tag[] = array(
				"tag"   => wp_strip_all_tags( $value ),
				"regx"  => wp_strip_all_tags( $regx[ $key ] ),
				"query" => wp_strip_all_tags( $query[ $key ] )
			);
		}
		$store = update_option( "boca-rewrite-tag", serialize( $rewrite_tag ) );
		session::set( [ "success" => "success" ] );

		return redierct()->back();
	} );
	Route::post( "/create-meta-boxes", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "name" ) || ! Request::hasInput( "id" ) || ! Request::hasInput( "post_type" ) || ! Request::hasInput( "type" ) ) {
			session::set( [ "error" => "input error 1" ] );

			return redierct()->back();
		}
		if ( empty( Request::input( "name" ) ) || empty( Request::input( "id" ) ) || empty( Request::input( "post_type" ) ) || empty( Request::input( "type" ) ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		$name          = wp_strip_all_tags( Request::input( "name" ) );
		$id            = wp_strip_all_tags( Request::input( "id" ) );
		$post_type     = rest_sanitize_array( Request::input( "post_type" ) );
		$type          = wp_strip_all_tags( Request::input( "type" ) );
		$array         = get_option( "boca-metaboxes-fields" );
		$custom_fields = $array ? unserialize( $array ) : [];
		if ( key_exists( $id, $custom_fields ) ) {
			session::set( [ "error" => "field is exists" ] );

			return redierct()->back();
		}
		$custom_fields[ $id ] = array( "name" => $name, "post_type" => $post_type, "type" => $type );
		$update_fields        = update_option( "boca-metaboxes-fields", serialize( $custom_fields ) );
		session::set( [ "success" => "success" ] );

		return redierct()->back();
	} );
	Route::post( "/edit-meta-boxes", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "name" ) || ! Request::hasInput( "id" ) || ! Request::hasInput( "post_type" ) || ! Request::hasInput( "type" ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		if ( empty( Request::input( "name" ) ) || empty( Request::input( "id" ) ) || empty( Request::input( "post_type" ) ) || empty( Request::input( "type" ) ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		$name          = wp_strip_all_tags( Request::input( "name" ) );
		$id            = wp_strip_all_tags( Request::input( "id" ) );
		$post_type     = rest_sanitize_array( Request::input( "post_type" ) );
		$type          = wp_strip_all_tags( Request::input( "type" ) );
		$array         = get_option( "boca-metaboxes-fields" );
		$custom_fields = $array ? unserialize( $array ) : [];
		if ( ! key_exists( $id, $custom_fields ) ) {
			session::set( [ "error" => "field not exists" ] );

			return redierct()->back();
		}
		$custom_fields[ $id ] = array( "name" => $name, "post_type" => $post_type, "type" => $type );

		$update_fields = update_option( "boca-metaboxes-fields", serialize( $custom_fields ) );
		session::set( [ "success" => "success" ] );

		return redierct()->back();
	} );
	Route::post( "/delete-meta-boxes", function () {
		if ( empty( Request::input( "_token_app" ) ) || ( session::get( "_token_app" ) != Request::input( "_token_app" ) ) ) {
			session::set( [ "error" => "error 401 Auth" ] );

			return redierct()->back();
		}
		if ( ! Request::hasInput( "id" ) || empty( Request::input( "id" ) ) ) {
			session::set( [ "error" => "input error" ] );

			return redierct()->back();
		}
		$id            = wp_strip_all_tags( Request::input( "id" ) );
		$array         = get_option( "boca-metaboxes-fields" );
		$custom_fields = $array ? unserialize( $array ) : [];

		if ( ! key_exists( $id, $custom_fields ) ) {
			session::set( [ "error" => "field not exists" ] );

			return redierct()->back();
		}
		unset( $custom_fields[ $id ] );
		$update_fields = update_option( "boca-metaboxes-fields", serialize( $custom_fields ) );
		session::set( [ "success" => "success" ] );
		return redierct()->back();
	} );
} );
