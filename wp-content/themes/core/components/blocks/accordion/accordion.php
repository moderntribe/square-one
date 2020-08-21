<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\accordion\Accordion_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Accordion_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?><?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>

		<?php get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_header_args()
		); ?>

		<div <?php echo $c->get_content_classes(); ?>>
			<?php get_template_part(
				'components/accordion/accordion',
				null,
				$c->get_content_args()
			); ?>
		</div>
	</div>
</section>
