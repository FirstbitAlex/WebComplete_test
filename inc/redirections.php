<?php
/**
 * All redirects which created from theme files
 *
 */

function demchco_theme__redirections() {

    if( is_singular('slug') || is_post_type_archive('slug') || is_tax('slug') ){
        wp_redirect( home_url(), 301 );
        exit;
    }
  
} 
// add_action( 'template_redirect', 'demchco_theme__redirections');

//Redirect from author page to home page
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
} );