<article class="loop-item">

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

	<footer>

		<ul class="entry-meta">

			<?php // Meta: Date ?>
			<li>
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php the_time( 'F j, Y' );?>
				</time>
			</li>

			<?php // Meta: Author ?>
			<li>
				by
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
					<?php the_author(); ?>
				</a>
			</li>

		</ul>

	</footer>

</article>
