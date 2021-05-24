<?php
declare( strict_types=1 );
use \Tribe\Project\Templates\Components\header\masthead\Masthead_Controller;

$c = Masthead_Controller::factory();
?>

<header class="site-header">

	<div class="l-container">

		<?php echo $c->get_logo(); ?>

		<?php get_template_part( 'components/header/navigation/navigation' ); ?>

	</div>

</header>
