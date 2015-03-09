<?php get_header(); ?>

	<main role="entry-content" itemprop="mainContentOfPage">
		<div class="content-wrap">

			<?php while ( have_posts() ) : the_post(); ?>
		
				<?php // Content: Post
				get_template_part( 'content/single/post' ); ?>

				<?php // Pagination
				tribe_post_nav(); ?>

				<?php // Comments
				if ( comments_open() || get_comments_number() )
					comments_template();
				?>
				
			<?php endwhile; ?>

		</div><!-- .content-wrap -->
	</main><!-- main -->	

<?php get_footer(); ?>