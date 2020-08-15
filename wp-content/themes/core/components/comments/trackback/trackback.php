<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\comments\trackback\Controller::factory( $args );
?>

<li <?php echo $c->classes(); ?><?php echo $c->attributes(); ?>>
	<p>
		<strong><?php echo esc_html( $c->label() ); ?></strong>

		<?php echo $c->trackback_link(); ?>

		<?php echo $c->edit_link(); ?>
	</p>
</li>
