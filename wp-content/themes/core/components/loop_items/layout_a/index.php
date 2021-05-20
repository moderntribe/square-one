<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\loop_items\layout_a\Index_Controller;

$c = Index_Controller::factory();
?>

<article class="item-loop item-loop--<?php echo esc_attr( get_post_type() ); ?>">

	<header class="item-loop__header">

		<h3 class="item-loop__title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title();  ?>

				<?php get_template_part( 'components/text/text', null, $c->get_taxonomy_args() ); ?>

			</a>
		</h3>

		<ul class="item-loop__meta">

			<li class="item-loop__meta-date">
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php echo esc_html( get_the_time( 'F j, Y' ) ); ?>
				</time>
			</li>

		</ul>

	</header>

	<?php if ( ! empty( $c->get_image_args() ) ) {
		get_template_part(
			'components/image/image',
			null,
			$c->get_image_args()
		);
	} ?>

	<?php the_excerpt(); ?>

	<footer class="item-loop__footer">

		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php echo __('Read more', 'tribe' ) ?> 
			<span class="u-visually-hidden"> <?php echo __('about ', 'tribe' ); the_title(); ?> </span>
		</a>

	</footer>

</article>
