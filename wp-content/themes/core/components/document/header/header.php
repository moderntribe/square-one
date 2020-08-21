<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\document\header\Header_Controller;

$c = Header_Controller::factory();
?>

<!DOCTYPE html>
<html <?php echo $c->get_language_attributes(); ?>>
<?php get_template_part( 'components/document/head/head' ); ?>
<body class="<?php echo esc_attr( $c->get_body_class() ); ?>">

	<?php do_action( 'tribe/body_opening_tag' ) ?>

	<div class="l-wrapper" data-js="site-wrap">

		<?php get_template_part( 'components/header/masthead/masthead' ); ?>
