<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\header\masthead\Controller::factory();
?>

<header class="site-header">

	<a
		href="#main-content"
		class="a11y-skip-link u-visually-hidden"
	>
		<?php esc_html_e( 'Skip to main content', 'tribe' ); ?>
	</a>

	<div class="l-container">

		<?php echo $c->logo(); ?>

		<?php get_template_part( 'components/header/navigation/navigation' ); ?>

		<?php get_template_part( 'components/content/search_form/search_form' ); ?>

	</div>

</header>
