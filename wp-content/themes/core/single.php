<?php get_header(); ?>

	<main>

		<?php // Content: Sub-header
		get_template_part( 'content/header/sub' ); ?>

		<div class="content-wrap">

			<?php while ( have_posts() ) : the_post(); ?>
		
				<?php // Content: Post
				get_template_part( 'content/single/post' ); ?>

				<?php // Content: Pagination
				get_template_part( 'content/pagination/single' ); ?>

				<?php // Comments
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				} ?>
				
			<?php endwhile; ?>

		</div>

	</main>

<?php get_footer(); ?>
