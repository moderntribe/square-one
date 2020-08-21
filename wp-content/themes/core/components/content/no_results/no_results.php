<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\content\no_results\No_Results_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = No_Results_Controller::factory( $args );
?>

<div <?php echo $c->get_classes(); ?>>

	<h3 class="no-results__title">
		<?php echo $c->get_title(); ?>
	</h3>

	<p class="no-results__content">
		<?php echo $c->get_content(); ?>
	</p>

</div>
