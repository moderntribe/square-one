<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\site_header\Site_Header_Controller;

$c = Site_Header_Controller::factory();
?>

<header  <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>

	<a href="#main-content" class="a11y-skip-link u-visually-hidden">
		<?php esc_html_e( 'Skip to main content', 'tribe' ); ?>
	</a>

	<div class="l-container c-site-header__container">

		<?php get_template_part( 'components/container/container', '', $c->get_logo_args() ); ?>

		<?php /* Mobile menu toggle */
		get_template_part( 'components/button/button', '', $c->get_flyout_toggle_args() ); ?>

		<div class="c-site-header__nav-container" id="nav-flyout" data-js="nav-flyout">

			<div class="c-site-header__search-flyout" id="search-flyout" data-js="search-flyout">
				<?php /* Search Form */
				get_template_part( 'components/search_form/search_form', '', $c->get_search_form_args() ); ?>
			</div>

			<?php /* Main Nav */
			get_template_part( 'components/navigation/navigation', '', $c->get_main_nav_args() ); ?>

			<?php /* Search toggle */
			get_template_part( 'components/button/button', '', $c->get_search_toggle_args() ); ?>

		</div>

	</div>

</header>
