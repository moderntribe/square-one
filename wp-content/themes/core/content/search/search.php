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
	$options = [ 'wrapper_class' => 'loop-item-featured-img', 'link' => get_permalink() ];
	the_tribe_image( get_post_thumbnail_id(), $options ); ?>

	<?php // Excerpt
	the_excerpt(); ?>

</article>
