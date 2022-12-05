<?php
defined( "ABSPATH" ) or die( '' );

use boca\core\settings\Hooks;


$array_accept_post = get_option( "boca-tax-accept-post" );
$accept_post       = $array_accept_post ? unserialize( $array_accept_post ) : [];
$array             = get_option( "boca-custom-taxonomy" );
$taxonomy_register = $array ? unserialize( $array ) : [];

$array_custom_fields = get_option( "boca-custom-fields-taxonomy" );
$custom_fields       = $array_custom_fields ? unserialize( $array_custom_fields ) : [];

if ( count( $taxonomy_register ) > 0 ) {
	Hooks::Init( "init", function () use ( $taxonomy_register, $accept_post ) {
		Hooks::action( function () use ( $taxonomy_register, $accept_post ) {
			foreach ( $taxonomy_register as $key => $value ) {
				register_taxonomy( $key, $accept_post [ $key ], $value );
			}
		} );
	} );
}

if ( count( $custom_fields ) > 0 ):
	foreach ( $custom_fields as $key => $value ):
		Hooks::Init( "{$value["taxonomies"]}_edit_form_fields", function () use ( $key, $value ) {
			Hooks::action( function ( $terms ) use ( $key, $value ) {
				if ( $value["type"] == "text" ):
					$_value = get_term_meta($terms->term_id , $key , true);
					?>
					<tr class="form-field term-slug-wrap">
						<th scope="row"><label for="<?php echo  $key ?>_input_id"><?php echo  $value["name"] ?></label></th>
						<td>
							<input id="<?php echo  $key ?>_input_id" type="text" name="<?php echo  $key ?>_input_name" value="<?php echo $_value ?>">
						</td>
					</tr>
				<?php
				endif;
			} );
		} );
	Hooks::Init("edited_{$value["taxonomies"]}",function () use($key ,$value) {
		Hooks::action(function ($term_id)use($key ,$value){
				if($value["type"] == "text"):
					update_term_meta($term_id , $key ,sanitize_text_field($_POST["{$key}_input_name" ]));
				endif;
		});
	});
	endforeach;
endif;
