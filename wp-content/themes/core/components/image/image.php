<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\image\Image_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Image_Controller::factory( $args );
?>

<<?php echo $c->get_wrapper_tag(); ?>
	<?php echo $c->get_classes(); ?>
	<?php echo $c->get_attrs(); ?>
>

	<?php if ( $c->has_link() ) { ?>
		<a
			href="<?php echo esc_url( $c->get_link_url() ); ?>"
			<?php echo $c->get_link_classes(); ?>
			<?php echo $c->get_link_attributes(); ?>
		>
	<?php } ?>

		<?php echo $c->get_image(); ?>

	<?php if ( ! empty( $c->has_link() ) ) { ?>
		</a>
	<?php } ?>

	<?php echo $c->get_html(); ?>

</<?php echo $c->get_wrapper_tag(); ?>>
