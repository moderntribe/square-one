<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\comments\trackback\Trackback_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Trackback_Controller::factory( $args );
?>

<li <?php echo $c->get_classes(); ?><?php echo $c->get_attrs(); ?>>
	<p>
		<strong><?php echo esc_html( $c->get_label() ); ?></strong>

		<?php echo $c->get_trackback_link(); ?>

		<?php echo $c->get_edit_link(); ?>
	</p>
</li>
