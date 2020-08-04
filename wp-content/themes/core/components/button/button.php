<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
$controller = \Tribe\Project\Templates\Components\button\Controller::factory( $args );

if ( ! $controller->has_content() ) {
	return;
}
?>

<?php echo $controller->wrapper_tag_open(); ?>

	<button <?php echo $controller->classes(); ?> <?php echo $controller->attributes(); ?>>
		<?php echo $controller->content(); ?>
	</button>

<?php echo $controller->wrapper_tag_close(); ?>
