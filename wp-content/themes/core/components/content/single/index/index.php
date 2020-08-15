<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\content\single\index\Controller::factory();
?>

<article class="item-single">

	<?php echo $c->render_featured_image(); ?>

	<div class="item-single__content s-sink t-sink l-sink l-sink--double">
		<?php the_content(); ?>
	</div>

	<footer class="item-single__footer l-container">

		<ul class="item-single__meta">

			<li class="item-single__meta-date">
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php the_date(); ?>
				</time>
			</li>

			<li class="item-single__meta-author">
				<?php _e( 'by', 'tribe' ); ?>
				<a href="<?php the_author_link(); ?>" rel="author">
					<?php the_author(); ?>
				</a>
			</li>

		</ul>

		<?php get_template_part( 'components/share/share' ) ?>

	</footer>
	<?php comments_template(); ?>
</article>
