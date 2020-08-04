<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\image\Controller::factory( $args );
?>

<<?php echo $controller->wrapper_tag(); ?>
	<?php echo $controller->wrapper_classes(); ?>
	<?php echo $controller->wrapper_attributes(); ?>
>

	<?php if ( ! empty( $controller->link_url ) ) { ?>
		<a
			href="<?php echo esc_url( $controller->link_url ); ?>"
			<?php echo $controller->link_classes(); ?>
			<?php echo $controller->link_attributes(); ?>
		>
	<?php } ?>

		<?php echo $controller->get_image(); ?>

	<?php if ( ! empty( $controller->link_url ) ) { ?>
		</a>
	<?php } ?>

	<?php echo $controller->html; ?>

</<?php echo $controller->wrapper_tag(); ?>>
