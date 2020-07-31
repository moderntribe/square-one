<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\routes\search\Controller::factory();

$controller->render_header();

?>
	<main id="main-content">
		<?php $controller->render_breadcrumbs(); ?>

		<div class="l-container">

			<?php
			if( have_posts() ) {
				echo "Results";
				while ( have_posts() ) {
					the_post();
					get_template_part( 'components/content/search_item/search_item', 'search' );
				}
				get_template_part( 'components/pagination/loop/loop', 'search' );
			}else{
				get_template_part( 'components/content/no_results/no_results', 'search' );
			}
			?>

		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();
