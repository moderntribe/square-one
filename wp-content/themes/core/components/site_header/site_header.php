<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\site_header\Site_Header_Controller;

$c = Site_Header_Controller::factory();
?>

<header class="c-site-header">

	<div class="l-container">

		<a href="#main-content" class="a11y-skip-link u-visually-hidden">
			<?php esc_html_e( 'Skip to main content', 'tribe' ); ?>
		</a>

		<?php get_template_part( 'components/container/container', '', $c->get_logo_args() ); ?>

		<div class="c-site-header__nav-container" data-js="c-site-header-nav-container">

			<?php /* Mobile menu toggle */
			get_template_part( 'components/button/button', null, $c->get_flyout_toggle_args() ); ?>

			<?php /* Main Nav */
			get_template_part( 'components/navigation/navigation', null, $c->get_main_nav_args() ); ?>

			<?php /* Search toggle */
			get_template_part( 'components/button/button', null, $c->get_search_toggle_args() ); ?>

			<?php /* Search Form */
			get_template_part( 'components/search_form/search_form', null, $c->get_search_form_args() ); ?>

		</div>

	</div>

</header>
