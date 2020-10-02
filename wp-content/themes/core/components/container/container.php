<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\container\Container_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c       = Container_Controller::factory( $args );
$content = $c->get_content();
if ( empty( $content ) ) {
	return;
}
?>
<<?php echo $c->get_tag(); ?>
<?php echo $c->get_classes(); ?>
<?php echo $c->get_attrs(); ?>
>
<?php echo $content; ?>
</<?php echo $c->get_tag(); ?>>
