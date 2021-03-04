<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\routes\index\Index_Controller;

$c = Index_Controller::factory();

$c->render_header();
?>
	<main id="main-content">
		<?php $c->render_breadcrumbs(); ?>

		<div class="l-container">

			<?php
			if ( have_posts() ) :

				get_template_part(
					'components/slider/slider',
					null,
					[
						Tribe\Project\Templates\Components\slider\Slider_Controller::SLIDES => [
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
						],
						Tribe\Project\Templates\Components\slider\Slider_Controller::SHOW_ARROWS     => true,
						Tribe\Project\Templates\Components\slider\Slider_Controller::SHOW_PAGINATION => true,
						Tribe\Project\Templates\Components\slider\Slider_Controller::SHOW_CAROUSEL   => true,
						Tribe\Project\Templates\Components\slider\Slider_Controller::CAROUSEL_SLIDES => [
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
							'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
						],
					]
				);

				get_template_part(
					'components/slider/slider',
					null,
					[
						Tribe\Project\Templates\Components\slider\Slider_Controller::SLIDES => [
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
								'<img src="https://square1.tribe/wp-content/uploads/2021/03/Schmitt_Music.png"/>',
						],
					]
				);

				while ( have_posts() ) :

					the_post();
					get_template_part( 'components/loop_items/index/index', 'index' );

				endwhile;

				get_template_part( 'components/pagination/loop/loop', 'index' );

			else :

				get_template_part( 'components/no_results/no_results', 'index' );

			endif;
			?>

		</div>
	</main>
<?php
$c->render_sidebar();
$c->render_footer();
