<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\document\header\Controller::factory();
?>

<!DOCTYPE html>
<html <?php echo $c->language_attributes(); ?>>
<?php get_template_part( 'components/document/head/head' ); ?>
<body class="<?php echo esc_attr( $c->body_class() ); ?>">

	<?php do_action( 'tribe/body_opening_tag' ) ?>

	<div class="l-wrapper" data-js="site-wrap">

		<?php get_template_part( 'components/header/masthead/masthead' ); ?>
