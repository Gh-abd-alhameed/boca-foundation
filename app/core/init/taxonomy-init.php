<?php
defined( "ABSPATH" ) or die( '' );

use boca\core\settings\Hooks;


$array_accept_post = get_option( "boca-tax-accept-post" );
$accept_post       = $array_accept_post ? unserialize( $array_accept_post ) : [];
$array             = get_option( "boca-custom-taxonomy" );
$taxonomy_register = $array ? unserialize( $array ) : [];


if ( count( $taxonomy_register ) > 0 ) {
	Hooks::Init( "init", function () use ( $taxonomy_register , $accept_post ) {
		Hooks::action( function () use ( $taxonomy_register , $accept_post  ) {
			foreach ( $taxonomy_register as $key => $value ) {
				register_taxonomy( $key, $accept_post [$key] , $value );
			}
		} );
	} );
}

