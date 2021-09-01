<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\pagination\single\Single_Pagination_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Routes\single\Single_Controller;

$c = Single_Controller::factory();

get_header();
?>

	<main id="main-content">

		<article class="item-single">
			<?php get_template_part( 'components/header/subheader/subheader', 'single', $c->get_subheader_args() ); ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="item-single__featured-image l-sink">
					<?php get_template_part( 'components/image/image', null, $c->get_featured_image_args() ); ?>
				</div>
			<?php endif; ?>

			<div class="item-single__content s-sink t-sink l-sink l-sink--double">
				<?php
				if ( have_posts() ) {
					the_post();

					the_content(); // Block Content Only
				}
				?>
			</div>

			<div class="l-sink l-sink--double">
				<div class="c-single-footer__content">
					<?php get_template_part( 'components/tags_list/tags_list', null, $c->get_tags_list_args() ); ?>
					<?php get_template_part( 'components/share/share' ) ?>
				</div>
				<div class="c-single-footer__author">
					<div class="c-single-footer__author-name">
						<?php echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
					</div>
					<div class="c-single-footer__author-description">
						<?php echo wp_kses_post( get_the_author_meta( 'description', $post->post_author ) ) ?>
					</div>
				</div>
			</div>

			<?php // comments_template(); ?>

		</article>

		<div class="l-sink l-sink--double">
			<?php get_template_part( 'components/pagination/single/single', null, [
					Single_Pagination_Controller::NEXT_LINK_LABEL     => esc_html__( 'Next article', 'tribe' ),
					Single_Pagination_Controller::PREVIOUS_LINK_LABEL => esc_html__( 'Previous article', 'tribe' ),
			]); ?>
		</div>

	</main>

<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'single',
	[ Sidebar_Controller::SIDEBAR_ID => $c->sidebar_id ]
);
get_footer();
