<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\routes\index\Controller::factory();

$c->render_header();
?>
	<main id="main-content">
		<?php $c->render_breadcrumbs(); ?>

		<div class="l-container">

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) :

					the_post();
					get_template_part( 'components/content/loop_items/index/index', 'index' );

				endwhile;

				get_template_part( 'components/pagination/loop/loop', 'index' );

			else :

				get_template_part( 'components/no_results/no_results', 'index' );

			endif;
			?>

		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();
