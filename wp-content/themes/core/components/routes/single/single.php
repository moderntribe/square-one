<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\routes\single\Controller::factory();

$controller->render_header();
?>
	<main id="main-content">
		<?php $controller->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ); ?>

		<div class="l-container">

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) :

					the_post();
					get_template_part( 'components/content/loop_items/single/single', 'single' );

				endwhile;

				get_template_part( 'components/pagination/loop/loop', 'single' );

			else :

				get_template_part( 'components/no_results/no_results', 'single' );

			endif;
			?>

		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();
