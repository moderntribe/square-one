<article class="<?php echo get_post_type(); ?> loop-item">

	<header>
		
		<?php // Title ?>
		<h3 class="loop-item-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>

	</header>

	<?php // Featured Image
	if ( has_post_thumbnail() ) { ?>
		<figure class="loop-item-featured-img">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
    			<?php the_post_thumbnail( 'core-full' ); ?>
			</a>
    	</figure>
	<?php } ?>

	<?php // Excerpt
	the_excerpt(); ?>

</article>
