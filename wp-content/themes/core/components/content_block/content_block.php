<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\content_block\Content_Block_Controller;
/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Content_Block_Controller::factory( $args );
?>

<<?php echo $c->get_tag(); ?>
	<?php echo $c->get_classes(); ?>
	<?php echo$c->get_attrs(); ?>
>

	<?php echo $c->render_leadin(); ?>

	<?php echo $c->render_title(); ?>

	<?php echo $c->render_content(); ?>

	<?php echo $c->render_cta(); ?>

</<?php echo $c->get_tag(); ?>>
