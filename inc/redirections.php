<?php
/**
 * All redirects which created from theme files
 *
 */

add_action( 'template_redirect', function () {
	if ( is_author() ) {
		wp_redirect( home_url(), 301 );
		exit;
	}
	// https://url/?
	if ( preg_match( '#(.*)\?$#i', $_SERVER['REQUEST_URI'] ) ) {
		wp_redirect( strtok( $_SERVER["REQUEST_URI"], '?' ), 301 );
		exit;
	}

	if (is_front_page() ) {
		wp_redirect(get_post_type_archive_link('article'), 301);
		exit;
	}
});
