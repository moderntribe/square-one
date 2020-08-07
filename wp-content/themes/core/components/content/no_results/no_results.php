<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\content\no_results\Controller::factory();
?>

<div class="no-results">

	<h3 class="no-results__title">
		<?php echo esc_html( __( 'No Posts', 'tribe' ) ); ?>
	</h3>

	<p class="no-results__content">
		<?php echo esc_html( __( 'Sorry, but there are currently no posts to see at this time.', 'tribe' ) ); ?>
	</p>

</div>
