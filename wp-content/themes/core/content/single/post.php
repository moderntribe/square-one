<article class="item-single">

	<?php // Featured Image
	$options = [ 'wrapper_class' => 'item-single__image' ];
	the_tribe_image( get_post_thumbnail_id(), $options ); ?>

	<div class="item-single__content t-typography">
		<?php the_content(); ?>
	</div>
	
	<footer class="item-single__footer">

		<ul class="item-single__meta">

			<li class="item-single__meta-date">
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php the_date();?>
				</time>
			</li>

			<li class="item-single__meta-author">
				by
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author">
					<?php the_author(); ?>
				</a>
			</li>

		</ul>

		<?php // Components: Social Share
		get_template_part( 'components/social/share' ); ?>

	</footer>

</article>
