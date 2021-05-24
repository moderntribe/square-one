<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\loop_items\search\Search_Controller;

$c = Search_Controller::factory();
?>

<article class="search-result search-result--<?php echo esc_attr( get_post_type() ); ?>">

	<h3 class="search-result__title"><?php echo esc_html( get_the_title() ); ?></h3>

	<div class="search-result__cta">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php the_permalink(); ?>
		</a>
	</div>

	<?php if ( ! empty( $c->get_image_args() ) ) {
		get_template_part(
			'components/image/image',
			null,
			$c->get_image_args()
		);
	} ?>

	<?php the_excerpt(); ?>

</article>
