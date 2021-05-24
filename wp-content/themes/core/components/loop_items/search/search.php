<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\loop_items\search\Search_Controller;

$c = Search_Controller::factory();
?>

<article class="search-result search-result--<?php echo esc_attr( get_post_type() ); ?>" data-js="use-target-link">

	<div class="search-result__body">
		<h2 class="search-result__title"><?php echo esc_html( get_the_title() ); ?></h2>

		<div class="search-result__excerpt">
			<?php the_excerpt(); ?>
		</div>

		<div class="search-result__cta">
			<a href="<?php the_permalink(); ?>" rel="bookmark" data-js="target-link">
				<?php the_permalink(); ?>
			</a>
		</div>
	</div>

	<div class="search-result__image">
		<?php if ( ! empty( $c->get_image_args() ) ) {
			get_template_part(
				'components/image/image',
				null,
				$c->get_image_args()
			);
		} ?>
	</div>

</article>
