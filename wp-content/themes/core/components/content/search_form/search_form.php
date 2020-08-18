<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\content\search_form\Controller::factory();
?>

<form <?php echo $c->classes(); ?> role="search" method="get" action="<?php echo esc_url( $c->action() ); ?>">

	<label class="c-search__label" for="s">
		<?php esc_html_e( 'Search', 'tribe' ); ?>
	</label>
	<input class="c-search__input" type="text" id="s" name="s" />

	<?php $c->render_button(); ?>

</form>
