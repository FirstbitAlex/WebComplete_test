<?php
$terms = get_the_terms(get_the_ID(), 'article-topic');
?>

<article class="post">
	<div class="img-wrap">
		<?php if (has_post_thumbnail()) {
			the_post_thumbnail('post-card', [
				'alt' 	=> trim(strip_tags(get_the_title())),
				'title' => trim(strip_tags(get_the_title())),
				'class' => 'post-img',
			]);
		} else {
		?>
			<div class="post__default-stub"></div>
		<?php } ?>
	</div>

	<div class="post__content">
		<div class="post__meta">
			<?php

			if ($terms && !is_wp_error($terms)) {
				foreach ($terms as $term) { ?>
					<span class="post__term">
						<?php echo esc_html($term->name); ?>
					</span>
			<?php };
			};
			?>
		</div>

		<h2 class="post__title">
			<a href="<?php echo get_permalink() ?>">
				<span class="screen-reader-text">
					<?php echo __('Go to article: ', 'arctest') . ' ' . $post->post_title ?>
				</span>

				<?php echo the_title() ?>
			</a>
		</h2>
	</div>
</article>
