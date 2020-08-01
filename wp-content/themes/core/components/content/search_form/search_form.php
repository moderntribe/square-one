<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\content\search_form\Controller::factory();
?>

<form class="c-search" role="search" method="get" action="<?php echo esc_url( $controller->action() ); ?>">

	<label class="c-search__label" for="s">
		<?php esc_html_e( 'Search', 'tribe' ); ?>
	</label>
	<input class="c-search__input" type="text" id="s" name="s" />

	<?php $controller->render_button(); ?>

</form>
