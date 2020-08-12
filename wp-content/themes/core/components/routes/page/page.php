<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\routes\page\Controller::factory();

$c->render_header();
?>

	<main id="main-content">

		<?php $c->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ); ?>

		<div class="l-container">

			<?php echo $c->render_featured_image(); ?>

			<div class="t-sink s-sink l-sink l-sink--double">
				<?php the_content(); ?>
			</div>

		</div>

	</main>

<?php
$c->render_sidebar();
$c->render_footer();
