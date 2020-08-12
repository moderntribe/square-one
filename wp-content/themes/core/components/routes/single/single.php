<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\routes\single\Controller::factory();

$c->render_header();
?>

	<main id="main-content">

		<?php $c->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ) ?>

		<div class="l-container">

			<?php get_template_part( 'components/content/single/index/index' ); ?>

		</div>

	</main>

<?php
$c->render_sidebar();
$c->render_footer();
