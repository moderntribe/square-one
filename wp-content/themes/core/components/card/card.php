<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\card\Card_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Card_Controller::factory( $args );
?>

<<?php echo $c->get_tag(); ?> <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<?php if ( ! empty( $c->render_image() ) ) { ?>
		<div <?php echo $c->get_media_classes(); ?>>
			<?php echo $c->render_image(); ?>
		</div>
	<?php } ?>

	<div <?php echo $c->get_content_classes(); ?>>

		<?php echo $c->render_meta_primary(); ?>

		<?php echo $c->render_title(); ?>

		<?php echo $c->render_meta_secondary(); ?>

		<?php echo $c->render_description(); ?>

		<?php get_template_part( 'components/container/container', null, $c->get_cta_args() ); ?>

	</div>

</<?php echo $c->get_tag(); ?>>
