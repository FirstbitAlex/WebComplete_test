<?php
get_header();
?>

<main id="primary">
	<section>
		<div class="container">
			<h1 class="page-title">
				<?php the_title() ?>
			</h1>
		</div>
	</section>

	<section class="section">
		<div class="container content-wrap">
			<?php the_content() ?>
		</div>
	</section>
</main>

<?php
get_footer();
