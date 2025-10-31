<?php

define("THEME_WPML_SLUG", "arctest"); // TODO: Заменить на слаг темы, будет показываться в переводах WMPL
define("THEME_SLUG", "arctest");      // TODO: Заменить на слаг темы

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '3.0.0');
}

if (! function_exists('theme_setup__setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function theme_setup__setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on arctest, use a find and replace
		 * to change THEME_WPML_SLUG to the name of your theme in all the template files.
		 */
		load_theme_textdomain(THEME_WPML_SLUG, get_template_directory() . '/languages');

		// TODO: нужно или нет?
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		/** ==============================================
		 *  register menus ===============================
		 * ===============================================
		 */
		register_nav_menus([
			'main_menu' => esc_html__('Main menu', THEME_WPML_SLUG),
		]);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		/** ==============================================
		 *  Add image sizes ==============================
		 * ===============================================
		 */
		add_image_size('post-card', 312, 336, true);
	}
endif;
add_action('after_setup_theme', 'theme_setup__setup');

/**
 * Enqueue scripts and styles.
 */
function theme_setup_scripts() {
	wp_enqueue_style('css-style', get_template_directory_uri() . '/style.min.css', [], filemtime(get_template_directory() . '/style.min.css'));

	wp_enqueue_script('jquery');
	wp_enqueue_script('js-site', get_template_directory_uri() . '/js/site.min.js', ['jquery'], null, true);

	wp_localize_script('js-site', 'articleAjax', ['ajax_url' => admin_url('admin-ajax.php'),]);
}
add_action('wp_enqueue_scripts', 'theme_setup_scripts');


/**
 * All functions for customizing and designing the admin area
 */
require get_template_directory() . '/inc/admin-customize.php';

/**
 * All pre_get_posts functions from the theme
 */
require get_template_directory() . '/inc/pre-get-post.php';

/**
 * All redirects which created from theme files
 */
require get_template_directory() . '/inc/redirections.php';

/**
 * Custom functions which enhance the theme by hooking into WordPress
 */
require get_template_directory() . '/inc/template-functions.php';

