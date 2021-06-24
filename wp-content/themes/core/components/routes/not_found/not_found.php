<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\not_found\Not_Found_Controller;

$c = Not_Found_Controller::factory();

$c->render_header();
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
$c->render_sidebar();
$c->render_footer();


