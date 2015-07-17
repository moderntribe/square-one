<article class="<?php echo get_post_type() .'-'. get_the_ID(); ?> loop-item">

	<header>
		
		<?php // Title ?>
		<h3 class="loop-item-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3><!-- .loop-item-title -->

	</header>

	<?php // Featured Image
	if ( has_post_thumbnail() ) { ?>
		<figure class="loop-item-featured-img">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
    			<?php
					$attr = array( 'class' => '' );
					echo get_the_post_thumbnail( get_the_ID(), 'tribe-full', $attr );
				?>
			</a>
    	</figure><!-- .loop-item-featured-img -->
	<?php } ?>

	<?php // Excerpt
	the_excerpt(); ?>

	<?php // Schema: Posts
	the_posts_schema_as_json_ld(); ?>

</article><!-- .loop-item -->