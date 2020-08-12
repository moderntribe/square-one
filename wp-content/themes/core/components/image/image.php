<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\image\Controller::factory( $args );
?>

<<?php echo $c->wrapper_tag(); ?>
	<?php echo $c->classes(); ?>
	<?php echo $c->attributes(); ?>
>

	<?php if ( ! empty( $c->link_url ) ) { ?>
		<a
			href="<?php echo esc_url( $c->link_url ); ?>"
			<?php echo $c->link_classes(); ?>
			<?php echo $c->link_attributes(); ?>
		>
	<?php } ?>

		<?php echo $c->get_image(); ?>

	<?php if ( ! empty( $c->link_url ) ) { ?>
		</a>
	<?php } ?>

	<?php echo $c->html; ?>

</<?php echo $c->wrapper_tag(); ?>>
