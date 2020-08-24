<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\statistic\Statistic_Controller;

/**
 * @var array $args Arguments passed to the template
 */

// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Statistic_Controller::factory( $args );
?>

<<?php echo $c->get_tag(); ?>
	<?php echo $c->get_classes(); ?>
	<?php echo $c->get_attrs(); ?>
>

	<?php echo $c->get_value(); ?>

	<?php echo $c->get_label(); ?>

</<?php echo $c->get_tag(); ?>>
