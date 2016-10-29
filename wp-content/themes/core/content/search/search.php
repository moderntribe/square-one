<article class="item-loop item-loop--search item-loop--<?php echo get_post_type(); ?>">

	<header class="item-loop__header">

		<h3 class="item-loop__title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>

	</header>

	<?php // Image
	$options = [ 'wrapper_class' => 'item-loop__image', 'link' => get_permalink() ];
	the_tribe_image( get_post_thumbnail_id(), $options ); ?>

	<?php the_excerpt(); ?>

</article>

