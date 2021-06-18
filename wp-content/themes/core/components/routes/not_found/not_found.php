<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\not_found\Not_Found_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

$c = Not_Found_Controller::factory();

get_header();
?>
	<main id="main-content">
		<?php $c->render_breadcrumbs(); ?>

		<div class="l-container">

			<div class="not-found">

				<h3 class="not-found__title">
					<?php echo esc_html( __( '404 Page Not Found' ) ); ?>
				</h3>

				<p class="no-found__content">
					<?php echo esc_html( __( 'The content you are looking for does not exist.' ) ); ?>
				</p>

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


