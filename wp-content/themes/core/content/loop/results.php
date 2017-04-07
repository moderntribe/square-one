<article class="item-loop item-loop--<?php echo get_post_type(); ?>">

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

	<footer class="item-loop__footer">

		<ul class="item-loop__meta">

			<li class="item-loop__meta-date">
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php the_time( 'F j, Y' );?>
				</time>
			</li>

			<li class="item-loop__meta-author">
				<?php esc_attr_e( 'by', 'tribe' ); ?>
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
					<?php the_author(); ?>
				</a>
			</li>

		</ul>

	</footer>

</article>
