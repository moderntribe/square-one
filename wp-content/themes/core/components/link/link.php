<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\link\Link_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Link_Controller::factory( $args );
?>
<a <?php echo $c->classes(); ?> <?php echo $c->attributes(); ?>>
	<?php echo $c->content(); ?>
</a>
