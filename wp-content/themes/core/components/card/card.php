<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\card\Card_Controller;

/*
add data-js to allow for a fully "linked" card, like we used to do:

https://inclusive-components.design/cards/

d.bind_events = function() {
	d.$el.body.on("click", ".use-target", function() {
		a.location = c(this).find(".is-target").attr("href")
	}).on(d.state.click, ".save-target", function(a) {
		a.stopPropagation()
	}),
};
card.style.cursor = 'pointer';
also scope stanford 125
*/

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Card_Controller::factory( $args );
?>

<<?php echo $c->get_tag(); ?>
	<?php echo $c->get_classes(); ?>
	<?php echo$c->get_attrs(); ?>
>

	<?php if ( ! empty( $c->get_image_args() ) ) { ?>
		<div <?php echo $c->get_media_wrapper_classes(); ?>>
			<?php get_template_part(
				'components/image/image',
				null,
				$c->get_image_args()
			); ?>
		</div>
	<?php } ?>

	<div <?php echo $c->get_body_wrapper_classes(); ?>>

		<?php if ( ! empty( $c->render_title() ) ) { ?>
			<header class="c-card__header c-card__section">

				<?php get_template_part(
					'components/container/container',
					null,
					$c->render_meta_primary()
				); ?>

				<?php echo $c->render_title(); ?>

				<?php  get_template_part(
					'components/container/container',
					null,
					$c->render_meta_secondary()
				); ?>

			</header>
		<?php } ?>

		<?php if ( ! empty( $c->render_content() ) ) { ?>
			<div class="c-card__content c-card__section">
				<?php echo $c->render_content(); ?>
			</div>
		<?php } ?>

		<?php if ( ! empty( $c->render_cta() ) ) {  ?>
			<footer class="c-card__footer c-card__section">
				<?php echo $c->render_cta(); ?>
			</footer>
		<?php } ?>

	</div>

</<?php echo $c->get_tag(); ?>>
