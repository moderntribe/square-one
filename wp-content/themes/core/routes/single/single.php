<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Routes\single\Single_Controller;

$c = Single_Controller::factory();

get_header();
?>

	<main id="main-content">

		<article class="item-single">
			<?php
			get_template_part( 'components/header/subheader/subheader', 'single', $c->get_subheader_args() ); ?>

			<div class="item-single__content s-sink t-sink l-sink l-sink--double">
				<?php
				if ( have_posts() ) {
					the_post();
					the_content(); // Block Content Only
				}
				?>
			</div>

			<div class="l-container">
				<div class="c-tag-meta">
					<?php
					get_template_part( 'components/tags_list/tags_list', null, $c->get_tags_list_args() );
					?>
					<?php get_template_part( 'components/share/share' ) ?>
				</div>
			</div>

			<?php get_template_part( 'components/footer/single_footer/single_footer', null, $c->get_content_footer_args() ); ?>

			<?php // comments_template(); ?>

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
