<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\site_header\Site_Header_Controller;

$c = Site_Header_Controller::factory();
?>

<header class="c-site-header">

	<div class="l-container">

		<?php echo $c->get_logo(); ?>

		<?php // get_template_part( 'components/header/navigation/navigation' ); ?>

	</div>

</header>
