<?php

$controller = \Tribe\Project\Templates\Components\routes\not_found\Controller::factory();

$controller->render_header();

?>
	<main id="main-content">
		<?php $controller->render_breadcrumbs(); ?>

		<div class="l-container">
				<aside class="not-found">

					<h3 class="not-found__title">
						<?php echo esc_html( __( '404 Page Not Found' ) ) ?>
					</h3>

					<p class="no-found__content">
						<?php echo esc_html( __( 'The content you are looking for does not exist.' ) ) ?>
					</p>

				</aside>
		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();


