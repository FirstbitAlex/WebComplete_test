<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 */

get_header();
?>

	<main id="primary" class="site-main">

		<article class="content-block">
			<div class="content-block__container">
				<div class="content-block__inner">
					<section class="gutenberg-content">
						<?php the_content(); ?>
					</section>
				</div>
			</div>
		</article>

	</main><!-- #main -->

<?php
get_footer();
