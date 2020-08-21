<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\content\loop_items\search\Search_Controller;

$c = Search_Controller::factory();
?>

<article class="item-loop item-loop--search item-loop--<?php echo esc_attr( get_post_type() ); ?>">

	<header class="item-loop__header">

		<h3 class="item-loop__title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php echo esc_html( get_the_title() ); ?>
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

</article>
