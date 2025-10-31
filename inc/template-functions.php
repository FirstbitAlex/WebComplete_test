<?php

add_action('init', 'register_post_type_article');
function register_post_type_article() {
	$labels = [
		'name'                  => __('Cтатті', 'arctest'),
		'singular_name'         => __('Стаття', 'arctest'),
		'menu_name'             => __('Cтатті', 'arctest'),
		'name_admin_bar'        => __('Cтаття', 'arctest'),
		'add_new'               => __('Додати нову', 'arctest'),
		'add_new_item'          => __('Додати нову статтю', 'arctest'),
		'new_item'              => __('Нова стаття', 'arctest'),
		'edit_item'             => __('Редагувати статтю', 'arctest'),
		'view_item'             => __('Дивитись статтю', 'arctest'),
		'all_items'             => __('Всі статті', 'arctest'),
		'search_items'          => __('Шукати статті', 'arctest'),
		'not_found'             => __('Статті не знайдені', 'arctest'),
		'not_found_in_trash'    => __('В кошику статті не знайдені', 'arctest'),
	];

	register_post_type('article', [
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'articles'],
		'show_in_rest'       => true,
		'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-format-aside',
		'capability_type'    => 'post',
		'hierarchical'       => false,
	]);
}

add_action('init', 'register_article_topic_taxonomy');
function register_article_topic_taxonomy() {
	$labels = [
		'name'                       => __('Тематики', 'arctest'),
		'singular_name'              => __('Тематика', 'arctest'),
		'search_items'               => __('Шукати тематику', 'arctest'),
		'popular_items'              => __('Популярні тематики', 'arctest'),
		'all_items'                  => __('Усі тематики', 'arctest'),
		'edit_item'                  => __('Редагувати тематику', 'arctest'),
		'update_item'                => __('Оновити тематику', 'arctest'),
		'add_new_item'               => __('Додати нову тематику', 'arctest'),
		'new_item_name'              => __('Нова тематика', 'arctest'),
		'separate_items_with_commas' => __('Розділяйте тематики комами', 'arctest'),
		'add_or_remove_items'        => __('Додати або видалити тематику', 'arctest'),
		'choose_from_most_used'      => __('Обрати з найуживаніших', 'arctest'),
		'not_found'                  => __('Тематики не знайдено', 'arctest'),
		'menu_name'                  => __('Тематики', 'arctest'),
	];

	register_taxonomy(
		'article-topic',
		['article'],
		[
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => ['slug' => 'article-topic'],
		]
	);
}

add_action('wp_ajax_filter_articles', 'filter_articles');
add_action('wp_ajax_nopriv_filter_articles', 'filter_articles');
function filter_articles() {
	$term_slug = isset($_POST['term_slug']) ? sanitize_text_field(wp_unslash($_POST['term_slug'])) : '';
	$is_initial = isset($_POST['initial']) && intval($_POST['initial']) === 1;

	$posts_per_page = $is_initial ? 9 : (intval($_POST['posts_per_page'] ?? 3));
	$offset = $is_initial ? 0 : (intval($_POST['offset'] ?? 0));

	$args = [
		'post_type'      => 'article',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	if ($term_slug) {
		$args['tax_query'] = [[
			'taxonomy' => 'article-topic',
			'field'    => 'slug',
			'terms'    => $term_slug,
		]];
	}

	$q = new WP_Query($args);

	if ($q->have_posts()) {
		while ($q->have_posts()) {
			$q->the_post();
			get_template_part('template-parts/content', 'article');
		}
		wp_reset_postdata();
	} else {
		echo '<p>' . esc_html__('Постов не найдено', 'arctest') . '</p>';
	}

	wp_die();
}
