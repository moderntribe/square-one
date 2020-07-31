<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\routes\index\Controller::factory();

$controller->render_header();

?>
	<main id="main-content">
		<?php $controller->render_breadcrumbs(); ?>

		<div class="l-container">

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) :

					the_post();
					get_template_part( 'components/content/loop_item/loop_item', 'index' );

				endwhile;

				get_template_part( 'components/pagination/loop/loop', 'index' );

			else :

				get_template_part( 'components/no_results', 'index' );

			endif;
			?>

		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();
