<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\routes\example_app\Controller::factory();

get_template_part( 'components/document/header/header');
?>
<div data-js="example-app" class="l-container s-sink t-sink">

</div>
<?php
get_template_part('components/document/footer/footer');
