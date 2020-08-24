<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\routes\page\Page_Controller;

$c = Page_Controller::factory();

$c->render_header();
?>

	<main id="main-content">

		<?php $c->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ); ?>

		<div class="l-container">

			<?php get_template_part( 'components/content/page/index/index' ); ?>

		</div>

	</main>

<?php
$c->render_sidebar();
$c->render_footer();
