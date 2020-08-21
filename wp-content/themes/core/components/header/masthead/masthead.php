<?php
declare( strict_types=1 );
use \Tribe\Project\Templates\Components\header\masthead\Masthead_Controller;

$c = Masthead_Controller::factory();
?>

<header class="site-header">

	<a href="#main-content" class="a11y-skip-link u-visually-hidden">
		<?php esc_html_e( 'Skip to main content', 'tribe' ); ?>
	</a>

	<div class="l-container">

		<?php echo $c->get_logo(); ?>

		<?php get_template_part( 'components/header/navigation/navigation' ); ?>

		<?php get_template_part( 'components/content/search_form/search_form' ); ?>

	</div>

</header>
