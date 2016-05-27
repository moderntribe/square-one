<article class="<?php echo get_post_type() .'-'. get_the_ID(); ?>">

	<?php // Featured Image
	$options = [ 'wrapper_class' => 'entry-featured-img' ];
	the_tribe_image( get_post_thumbnail_id(), $options ); ?>
	
	<?php // Content ?>		
	<div class="context-content">
		<?php the_content(); ?>
	</div>
	
	<footer>

		<ul class="entry-meta">

			<?php // Meta: Date ?>
			<li>
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php the_date();?>
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

</article><!-- post -->
