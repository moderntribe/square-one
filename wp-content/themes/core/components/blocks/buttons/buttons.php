<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\blocks\buttons\Buttons_Block_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Buttons_Block_Controller::factory( $args );

if ( empty( $c->get_buttons() ) ) {
	return;
}
?>

<div <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php foreach ( $c->get_buttons() as $button ) { ?>
		<?php get_template_part( 'components/link/link', null, $button ); ?>
	<?php } ?>
</div>
