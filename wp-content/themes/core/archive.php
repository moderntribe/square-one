<?php get_header(); ?>

	<main>

		<?php // Content: Sub-header
		get_template_part( 'content/header/sub' ); ?>

		<div class="l-wrapper">
		
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php // Content: Loop Results
					get_template_part( 'content/loop/results' ); ?>

				<?php endwhile; ?>

				<?php // Content: Pagination
				get_template_part( 'content/pagination/loop' ); ?>

			<?php else : ?>

				<?php // Content: No Results
				get_template_part( 'content/no-results' ); ?>

			<?php endif; ?>

		</div>

	</main>

<?php get_footer(); ?>
