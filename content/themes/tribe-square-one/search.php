<?php get_header(); ?>

	<main class="loop-content" role="main" itemprop="mainContentOfPage">
		<div class="content-wrap">

			<h1 class="loop-title" itemprop="name">Search results for: <?php echo get_search_query(); ?></h1>
		
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php // Content: Search Results
					get_template_part( '/content/search/search' ); ?>

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