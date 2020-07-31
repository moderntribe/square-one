<?php

$controller = \Tribe\Project\Templates\Components\routes\not_found\Controller::factory();

$controller->render_header();

?>
	<main id="main-content">
		<?php $controller->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ); ?>

		<div class="l-container">
			<div class="l-container">
				<h2>{{ error_404_browser_title }}</h2>
				<p>{{ error_404_browser_content }}</p>
			</div>
		</div>
	</main>
<?php
$controller->render_sidebar();
$controller->render_footer();


