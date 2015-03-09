<?php get_header(); ?>

	<main class="loop-content" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
		<div class="content-wrap">

			<h1 class="loop-title" itemprop="name">Index</h1>
		
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php // Content: Loop Results
					get_template_part( '/content/loop/results' ); ?>

				<?php endwhile; ?>

				<?php // Pagination
				tribe_paging_nav(); ?>

			<?php else : ?>

				<?php // Content: No Results
				get_template_part( '/content/no-results' ); ?>

			<?php endif; ?>

		</div><!-- .content-wrap -->
	</main><!-- main -->	

<?php get_footer(); ?>