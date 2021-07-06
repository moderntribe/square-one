<?php declare(strict_types=1);

use \Tribe\Project\Templates\Routes\not_found\Not_Found_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

$c = Not_Found_Controller::factory();

get_header();
?>
	<main id="main-content">

		<div class="l-container t-sink">

			<div class="not-found">

				<h1 class="not-found__title h1">
					<?php echo esc_html( __( 'Error 404: I’m pretty sure you broke it' ) ); ?>
				</h1>

				<p class="not-found__content">
					<?php printf(
						__( 'Start over from <a href="%s">home</a>, use the navigation to get back on track or search for something.' ),
						esc_url( get_site_url() ),
					); ?>
				</p>

				<div class="not-found__search">
					<?php get_template_part( 'components/search_form/search_form', null, $c->get_search_form_args() ); ?>
				</div>

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


