<?php
get_header();

global $wp_query;
$posts_per_page = get_option('posts_per_page');
$is_show_load_more = (int)($wp_query->found_posts > $posts_per_page);

$terms = get_terms([
	'taxonomy' => 'article-topic',
	'hide_empty' => false,
]);

$current_topic = '';
if (isset($_GET['article-topic']) && !empty($_GET['article-topic'])) {
	$current_topic = sanitize_text_field(wp_unslash($_GET['article-topic']));
}
?>

<main id="primary">
	<!-- PAGE TITLE -->
	<header>
		<div class="container">
			<h1 class="page-title">
				<?php _e('The Journal', 'arctest'); ?>
			</h1>
		</div>
	</header>

	<!-- FILTERS LIST  -->
	<section class="section">
		<div class="container">
			<nav
				class="filter-nav"
				aria-label="<?php esc_attr_e('Article topics', 'arctest'); ?>">
				<button
					class="filter-nav__item<?php echo $current_topic === '' ? ' active' : ''; ?>"
					data-term-slug=""
					type="button">
					<?php echo esc_html__('All', 'arctest'); ?>
				</button>

				<?php if (! is_wp_error($terms) && ! empty($terms)) { ?>
					<?php foreach ($terms as $term) {
						$slug = $term->slug;
						$name = $term->name;
						$active = ($current_topic === $slug) ? ' active' : '';
					?>
						<button
							class="filter-nav__item<?php echo esc_attr($active); ?>"
							data-term-slug="<?php echo esc_attr($slug); ?>"
							type="button">
							<?php echo esc_html($name); ?>
						</button>
					<?php } ?>
				<?php } ?>
			</nav>

			<div class="nav-line"></div>
		</div>
	</section>

	<!-- POSTS LIST -->
	<section class="section" aria-labelledby="article-list-heading">
		<div class="container">
			<h2 class="screen-reader-text" id="article-list-heading">
				<?php _e('Articles list', 'arctest'); ?>
			</h2>

			<div class="post-list" id="post-list" role="region" aria-live="polite" aria-labelledby="article-list-heading">
				<?php
				if (have_posts()) {
					while (have_posts()) {
						the_post();
						get_template_part('template-parts/content', 'article');
					};
					wp_reset_postdata();
				} else {
					get_template_part('template-parts/content', 'none');
				};
				?>
			</div>
		</div>
	</section>

	<!-- LOAD MORE -->
	<section class="section section--load-more <?php if ($is_show_load_more) echo 'show' ?>">
		<div class="container">
			<div class="load-more__wrap">
				<button
					class="load-more__item"
					id="load-more"
					data-slug=""
					data-loadmore_ppp="3"
					data-offset="<?php echo esc_attr($posts_per_page) ?>"
					type="button">

					<span class="screen-reader-text">
						<?php _e('Show more articles', 'arctest') ?>
					</span>

					<?php _e('LOAD MORE', 'arctest') ?>
				</button>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
