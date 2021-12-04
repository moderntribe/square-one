<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\blocks\section_nav\Section_Nav_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Section_Nav_Block_Controller::factory( $args );
?>

<section <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<div <?php echo $c->get_container_classes(); ?>>
		<?php get_template_part(
			'components/section_nav/section_nav',
			null,
			$c->get_section_nav_args()
		); ?>
	</div>
</section>
