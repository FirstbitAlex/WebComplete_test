<?php

/**
 * All functions for customizing and designing the admin area
 */
if ( ! defined( 'ICL_LANGUAGE_CODE' ) ) {
	// Replace the ICL_LANGUAGE_CODE if not defined.
	define( 'ICL_LANGUAGE_CODE', 'uk' );
}
/**
 * Remove comments and default posts
 */
function my_remove_admin_menus() {
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'my_remove_admin_menus' );

/**
 * Disable WordPress Big Image Size Scaling
 */
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Remove default image sizes
 */
function remove_default_image_sizes( $sizes ) {
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'remove_default_image_sizes' );

/**
 * Remove empty paragraphs
 */
add_filter( 'the_content', 'remove_empty_p' );
add_filter( 'the_excerpt', 'remove_empty_p' );
function remove_empty_p( $content ) {
	return str_ireplace( array( '<p>&nbsp;</p>', '<p></p>' ), '', $content );
}

/*
* DISALLOW_FILE_EDIT - turning off edit files from theme editor in admin area
*/
define( 'DISALLOW_FILE_EDIT', true );

function limit_upload_size( $file ) {

	$image_limit = 5 * 1024 * 1024;  // 5 MB
	$file_limit  = 50 * 1024 * 1024;  // 50 MB

	$image_types = array( 'image/jpeg', 'image/png', 'image/gif' );

	// Перевірка чи файл є зображенням
	if ( in_array( $file['type'], $image_types ) ) {
		if ( $file['size'] > $image_limit ) {
			$file['error'] = 'Зображення не може перевищувати 5MB.';
		}
	} elseif ( $file['size'] > $file_limit ) {
		$file['error'] = 'Файл не може перевищувати 50MB.';
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'limit_upload_size' );

function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// wp
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
	// bbpress
	unset( $wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now'] );
	// yoast seo
	unset( $wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['wpseo-wincher-dashboard-overview'] );
	// gravity forms
	unset( $wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard'] );
}
add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets', 999 );
