<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\search\Search_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

$c = Search_Controller::factory();

get_header();
?>
	<main id="main-content">

		<div class="l-container">
			<div class="search-results__container">
				<h1 class="h2 search-results__title"><?php _e( 'Search', 'tribe' ); ?></h1>

				<?php get_template_part( 'components/search_form/search_form', null, $c->get_search_form_args() ); ?>

				<?php get_template_part( 'components/text/text', null, $c->get_results_text_args() ); ?>

				<?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post();
						get_template_part( 'components/card/card', 'search', $c->get_card_args() );
					endwhile;
					get_template_part( 'components/pagination/loop/loop', 'search' );
				endif;
				?>

			</div>
		</div>
	</main>
<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'index',
	[ Sidebar_Controller::SIDEBAR_ID => $c->sidebar_id ]
);
get_footer();
