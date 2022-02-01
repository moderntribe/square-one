<?php declare(strict_types=1);

use Tribe\Project\Templates\Routes\example_app\Example_App_Controller;

// phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
$c = Example_App_Controller::factory();

get_header();
?>

	<div data-js="example-app" class="l-container s-sink t-sink"></div>

<?php
get_footer();
