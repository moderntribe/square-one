<?php get_header(); ?>

	<main class="entry-content" role="main">

		<?php // Content: Sub-header
		get_template_part( 'content/header/sub' ); ?>

		<div class="content-wrap">

			<?php while ( have_posts() ) : the_post(); ?>
		
				<?php // Content: Post
				get_template_part( 'content/single/post' ); ?>

				<?php // Pagination
				tribe_post_nav(); ?>

				<?php // Comments
				if ( comments_open() || get_comments_number() )
				comments_template(); ?>

				<?php // Schema: Posts
				the_posts_schema_as_json_ld(); ?>
				
			<?php endwhile; ?>

		</div><!-- .content-wrap -->

	</main><!-- main -->	

<?php get_footer(); ?>