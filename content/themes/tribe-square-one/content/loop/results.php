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
    			<?php the_post_thumbnail( 'tribe-full' ); ?>
			</a>
    	</figure><!-- .loop-item-featured-img -->
	<?php } ?>

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

		</ul><!-- .entry-meta -->

	</footer>

	<?php // Schema: Posts
	the_posts_schema_as_json_ld(); ?>

</article><!-- .loop-item -->