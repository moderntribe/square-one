<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\blocks\tabs\Tabs_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Tabs_Block_Controller::factory( $args );
?>

<section <?php echo $c->classes(); ?> <?php echo $c->attrs(); ?>>
	<div <?php echo $c->container_classes(); ?>>
		<?php get_template_part( 'components/content_block/content_block', null, $c->get_header_args() ); ?>
		<?php get_template_part( 'components/tabs/tabs', null, $c->get_tabs_args() ); ?>
	</div>
</section>
