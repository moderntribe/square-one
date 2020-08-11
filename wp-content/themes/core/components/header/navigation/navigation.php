<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\header\navigation\Controller::factory();

if ( ! $c->has_menu() ) {
	return;
}
?>
<nav class="site-header__nav" aria-label="<?php echo esc_attr( $c->label() ); ?>">

	<ol class="site-header__nav-list">
		<?php echo $c->menu(); ?>
	</ol>

</nav>

