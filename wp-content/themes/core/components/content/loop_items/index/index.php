<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\content\loop_items\index\Index_Controller;

$c = Index_Controller::factory();
?>

<article class="item-loop item-loop--<?php echo esc_attr( get_post_type() ); ?>">

	<header class="item-loop__header">

		<h3 class="item-loop__title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>

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

		<ul class="item-loop__meta">

			<li class="item-loop__meta-date">
				<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
					<?php echo esc_html( get_the_time( 'F j, Y' ) ); ?>
				</time>
			</li>

			<li class="item-loop__meta-author">
				<?php
				printf(
					esc_html_x( 'by %s', 'link to the author archive', 'tribe' ),
					sprintf(
						'<a href="%s" rel="author">%s</a>',
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						esc_html( get_the_author() )
					)
				);
				?>
			</li>

		</ul>

	</footer>

</article>
