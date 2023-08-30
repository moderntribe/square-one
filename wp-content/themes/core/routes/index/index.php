<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Routes\index\Index_Controller;

$c = Index_Controller::factory();

get_header();
?>
	<main id="main-content">
		<?php get_template_part( 'components/subheader/subheader', 'index', $c->get_subheader_args() ); ?>

		<?php if ( have_posts() ) {
			// Featured posts
			if ( $c->is_page_one() && ! empty( $c->get_content_loop_featured_args() ) ) {
				get_template_part( 'components/blocks/content_loop/content_loop', '', $c->get_content_loop_featured_args() );
			}

			get_template_part( 'components/blocks/content_loop/content_loop', '', $c->get_content_loop_args() );
		} else {
			get_template_part( 'components/no_results/no_results', 'index' );
		} ?>
	</main>
<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'index',
	[ Sidebar_Controller::SIDEBAR_ID => $c->get_sidebar_id() ]
);

get_footer();
