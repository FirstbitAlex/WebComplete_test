<?php 

/**
 * Реєстрація Гутенберг блоків
 */
function register_acf_blocks() {
	$acf_blocks = glob( get_template_directory() . '/build/blocks/*' );

	foreach ( $acf_blocks as $block ) {
		register_block_type( $block );
	}
}
add_action( 'init', 'register_acf_blocks' );


/**
 * Фільтр, що модифікує налаштування редактора
 * та забороняє використовувати H1 у блоках "core/heading".
 */
function disable_h1_in_gutenberg( $args, $block_type ) {

	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Remove H1
	$args['attributes']['levelOptions']['default'] = array( 2, 3, 4, 5, 6 );

	return $args;
}
add_filter( 'register_block_type_args', 'disable_h1_in_gutenberg', 10, 2 );
?>