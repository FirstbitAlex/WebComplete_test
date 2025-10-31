<?php
/**
 * @param $query
 */
function pre_get_posts($query) {
	if (!is_admin() && $query->is_main_query()) {
		if ($query->is_post_type_archive('article')) {
			$query->set('posts_per_page', 9);
			$query->is_tax = false;

			// if (isset($_GET['category']) && !empty($_GET['category'])) {
			// 	$query->set('category_name', $_GET['category']);
			// }
		}
	}
}
add_action('pre_get_posts', 'pre_get_posts');
