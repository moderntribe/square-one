<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\accordion\Accordion_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Accordion_Block_Controller::factory( $args );
?>

<section <?php echo $c->classes(); ?><?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?><?php echo $c->container_attrs(); ?>>

		<?php echo $c->render_header(); ?>

		<div <?php echo $c->content_classes(); ?>>
			<?php echo $c->render_content(); ?>
		</div>

	</div>
</section>
