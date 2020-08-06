<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\routes\page\Controller::factory();

$controller->render_header();
?>

	<main id="main-content">

		<?php $controller->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ); ?>

		<div class="l-container">

			<?php echo $controller->render_featured_image(); ?>

			<div class="t-sink s-sink l-sink l-sink--double">

				<?php  get_template_part( 'components/quote/quote' );  ?>

				<?php the_content(); ?>
			</div>

		</div>

	</main>

<?php
$controller->render_sidebar();
$controller->render_footer();
