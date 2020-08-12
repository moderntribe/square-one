<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\footer\navigation\Controller::factory();

if ( ! $c->has_menu() ) {
	return;
}
?>

<nav class="site-footer__nav" aria-labelledby="site-footer__nav-label">

	<h2 id="site-footer__nav-label" class="u-visually-hidden"><?php echo esc_html( $c->label() ); ?></h2>

	<ol class="site-footer__nav-list">
		<?php echo $c->menu(); ?>
	</ol>

</nav>
