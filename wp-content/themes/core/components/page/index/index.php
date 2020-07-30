<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\page\index\Controller::factory();

$controller->render_header();

?>
	<main id="main-content">
		<?php $controller->render_breadcrumbs(); ?>

		<div class="l-container">

			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'components/content/loop_item/loop_item', 'index' );
			}

			get_template_part( 'components/pagination/loop/loop', 'index' );
			?>

		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();
