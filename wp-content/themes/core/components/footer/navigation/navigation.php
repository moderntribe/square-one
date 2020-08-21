<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\footer\navigation\Navigation_Controller;

$c = Navigation_Controller::factory();

if ( ! $c->has_menu() ) {
	return;
}
?>

<nav class="site-footer__nav" aria-label="<?php echo esc_html( __( 'Secondary Navigation', 'tribe' ) ); ?>">

	<ol class="site-footer__nav-list">
		<?php echo $c->get_menu(); ?>
	</ol>

</nav>
