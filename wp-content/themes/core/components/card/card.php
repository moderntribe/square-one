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

<<?php echo $c->get_tag(); ?> <?php echo $c->get_classes(); ?> <?php echo$c->get_attrs(); ?>>

	<?php if ( ! empty( $c->render_image() ) ) { ?>
		<div class="c-card__media">
			<?php echo $c->render_image(); ?>
		</div>
	<?php } ?>

	<div class="c-card__content">

		<?php echo $c->render_meta_primary(); ?>

		<?php echo $c->render_title(); ?>

		<?php echo $c->render_meta_secondary(); ?>

		<?php echo $c->render_description(); ?>

		<?php get_template_part( 'components/container/container', null, $c->get_cta_args() ); ?>

	</div>

</<?php echo $c->get_tag(); ?>>
