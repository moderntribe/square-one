<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\header\navigation\Controller::factory();

if ( ! $controller->has_menu() ) {
	return;
}
?>
<nav class="site-header__nav" aria-labelledby="site-header__nav-label">

	<h2 id="site-header__nav-label" class="u-visually-hidden"><?php echo esc_html( $controller->label() ); ?></h2>

	<ol class="site-header__nav-list">
		<?php echo $controller->menu(); ?>
	</ol>

</nav>

