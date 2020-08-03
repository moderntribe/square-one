<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\routes\single\Controller::factory();

get_template_part( 'components/document/header/header', 'single' );
?>
	<main id="main-content">

		<?php get_template_part( 'components/breadcrumbs/breadcrumbs', null, [ 'breadcrumbs' => $controller->get_breadcrumbs() ] ) ?>

		<?php get_template_part( 'components/header/subheader/subheader' ) ?>

		<div class="l-container">

			<?php get_template_part( 'components/content/loop_items/single/single', null ) ?>

		</div>
	</main>
<?php
get_template_part( 'components/sidebar/sidebar', 'single', [ 'sidebar_id' => $controller->get_sidebar() ] );
$controller->render_footer();
