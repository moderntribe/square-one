<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\routes\not_found\Not_Found_Controller;

$c = Not_Found_Controller::factory();

$c->render_header();
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
$c->render_sidebar();
$c->render_footer();


