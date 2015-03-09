<?php get_header(); ?>

	<main class="page-content" role="main" itemprop="mainContentOfPage">
		<div class="content-wrap">
		
			<?php while ( have_posts() ) : the_post(); ?>
		
				<?php // Content: Page
				get_template_part( '/content/page/default' ); ?>
				
			<?php endwhile; ?>

		</div><!-- .content-wrap -->
	</main><!-- main -->

	<?php get_sidebar(); ?>	

<?php get_footer(); ?>