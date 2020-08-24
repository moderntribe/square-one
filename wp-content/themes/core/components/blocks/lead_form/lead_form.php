<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\lead_form\Lead_Form_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Lead_Form_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php if ( ! empty( $c->get_content_args() ) ) { ?>
			<?php get_template_part(
				'components/content_block/content_block',
				null,
				$c->get_header_args()
			); ?>
		<?php } ?>

		<?php if ( $c->get_form_id() ) { ?>
			<div <?php echo $c->get_form_classes(); ?>>
				<?php echo $c->get_form(); ?>
			</div>
		<?php } ?>

	</div>
</section>
