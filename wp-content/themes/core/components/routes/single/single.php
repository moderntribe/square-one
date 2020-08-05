<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\routes\single\Controller::factory();

$controller->render_header();
?>

	<main id="main-content">

		<?php $controller->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ) ?>

		<div class="l-container">

			<?php get_template_part( 'components/content/single/index/index' ); ?>

		</div>

	</main>

<?php
$controller->render_sidebar();
$controller->render_footer();
