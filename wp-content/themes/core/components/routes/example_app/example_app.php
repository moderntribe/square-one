<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\example_app\Example_App_Controller;

$c = Example_App_Controller::factory();

get_header();
?>

	<div data-js="example-app" class="l-container s-sink t-sink"></div>

<?php
get_footer();
