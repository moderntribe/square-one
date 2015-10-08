<?php get_header(); ?>

	<main class="loop-content" role="main">

		<?php // Content: Sub-header
		get_template_part( 'content/header/sub' ); ?>

		<div class="content-wrap">
		
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php // Content: Search Results
					get_template_part( 'content/search/search' ); ?>

				<?php endwhile; ?>

				<?php // Content: Pagination
				get_template_part( 'content/pagination/loop' ); ?>

			<?php else : ?>

				<?php // Content: No Results
				get_template_part( 'content/no-results' ); ?>

			<?php endif; ?>

		</div><!-- .content-wrap -->

	</main><!-- main -->	

<?php get_footer(); ?>