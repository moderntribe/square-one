<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\blocks\tabs\Tabs_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Tabs_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>
		<?php
		get_template_part(
			'components/content_block/content_block',
			null,
			$c->get_header_args()
		);
		get_template_part(
			'components/tabs/tabs',
			null,
			$c->get_tabs_args()
		);
		?>
	</div>
</section>
