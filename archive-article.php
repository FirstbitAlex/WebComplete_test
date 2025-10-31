<?php
get_header();

global $wp_query;
$total_found = $wp_query->found_posts;
$initial_shown = 9;

$terms = get_terms([
	'taxonomy' => 'article-topic',
	'hide_empty' => false,
]);

$current_topic = '';
if (isset($_GET['article-topic']) && ! empty($_GET['article-topic'])) {
	$current_topic = sanitize_text_field(wp_unslash($_GET['article-topic']));
}
?>

<main id="primary">
	<section>
		<div class="container">
			<h1 class="page-title">
				<?php _e('The Journal', 'arctest'); ?>
			</h1>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<nav class="filter-nav">
				<button class="filter-nav__item<?php echo $current_topic === '' ? ' active' : ''; ?>" data-term-slug="">
					<?php echo esc_html__('All', 'arctest'); ?>
				</button>

				<?php if (! is_wp_error($terms) && ! empty($terms)) { ?>
					<?php foreach ($terms as $term) {
						$slug = $term->slug;
						$name = $term->name;
						$active = ($current_topic === $slug) ? ' active' : '';
					?>
						<button class="filter-nav__item<?php echo $active; ?>" data-term-slug="<?php echo esc_attr($slug); ?>">
							<?php echo esc_html($name); ?>
						</button>
					<?php } ?>
				<?php } ?>
			</nav>

			<div class="nav-line"></div>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="post-list" id="post-list">
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

	<section class="section section--load-more <?php if ($total_found > $initial_shown) echo 'show' ?>">
		<div class="container">
			<div class="load-more__wrap">
				<button class="load-more__item" data-page="1" id="load-more">
					<span class="screen-reader-text">
						<?php _e('Download more', 'arctest') ?>
					</span>

					<?php _e('LOAD MORE', 'arctest') ?>
				</button>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
