<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\routes\search\Search_Controller;

$c = Search_Controller::factory();

$c->render_header();
?>
	<main id="main-content">
		<?php $c->render_breadcrumbs(); ?>

		<div class="l-container">

			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					get_template_part( 'components/content/loop_items/search/search', 'search' );
				endwhile;
				get_template_part( 'components/pagination/loop/loop', 'search' );
			else :
				get_template_part( 'components/no_results/no_results', 'search' );
			endif;
			?>

		</div>
	</main>
<?php
$c->render_sidebar();
$c->render_footer();
