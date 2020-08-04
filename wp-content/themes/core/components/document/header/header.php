<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\document\header\Controller::factory();
?>
<!DOCTYPE html>
<html <?php echo $controller->language_attributes(); ?>>
<?php get_template_part( 'components/head/head' ); ?>
<body class="<?php echo esc_attr( $controller->body_class() ); ?>">

	<?php do_action( 'tribe/body_opening_tag' ) ?>

	<div class="l-wrapper" data-js="site-wrap">

		<?php get_template_part( 'components/header/masthead/masthead' ); ?>
