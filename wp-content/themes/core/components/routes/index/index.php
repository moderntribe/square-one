<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\index\Index_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

$c = Index_Controller::factory();

get_header();
?>
	<main id="main-content">
		<?php get_template_part( 'components/header/subheader/subheader', 'index', $c->get_subheader_args() ); ?>

		<?php if ( have_posts() ) :
			if ( $c->get_current_page() === 1 && ! empty( $c->get_content_loop_featured_data() ) ) :
				get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_content_loop_featured_data() );
			endif;
			get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_content_loop_data() ); ?>
		<?php else :
			get_template_part( 'components/no_results/no_results', 'index' );
		endif; ?>
	</main>
<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'index',
	[ Sidebar_Controller::SIDEBAR_ID => $c->sidebar_id ]
);

get_footer();
