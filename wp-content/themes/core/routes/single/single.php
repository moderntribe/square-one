<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\breadcrumbs\Breadcrumbs_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Routes\single\Single_Controller;

$c = Single_Controller::factory();

get_header();
?>

	<main id="main-content">

		<?php
		get_template_part(
			'components/breadcrumbs/breadcrumbs',
			'single',
			[ Breadcrumbs_Controller::BREADCRUMBS => $c->get_breadcrumbs() ]
		);
		?>

		<?php get_template_part( 'components/header/subheader/subheader' ) ?>

		<article class="item-single">

			<?php if ( ! empty( $c->get_image_args() ) ) {
				get_template_part(
					'components/image/image',
					null,
					$c->get_image_args()
				);
			} ?>

			<div class="item-single__content s-sink t-sink l-sink l-sink--double">
				<?php the_content(); ?>
			</div>

			<footer class="item-single__footer l-container">

				<ul class="item-single__meta">

					<li class="item-single__meta-date">
						<time datetime="<?php echo esc_attr( get_the_time( 'c' ) ); ?>">
							<?php the_date(); ?>
						</time>
					</li>

					<li class="item-single__meta-author">
						<?php _e( 'by', 'tribe' ); ?>
						<?php the_author_link(); ?>
					</li>

				</ul>

				<?php get_template_part( 'components/share/share' ) ?>

			</footer>

			<?php comments_template(); ?>

		</article>

	</main>

<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'single',
	[ Sidebar_Controller::SIDEBAR_ID => $c->sidebar_id ]
);
get_footer();
