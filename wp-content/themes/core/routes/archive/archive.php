<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Routes\archive\Archive_Controller;

$c = Archive_Controller::factory();

get_header();
?>
	<main id="main-content">
		<?php get_template_part( 'components/header/subheader/subheader', 'index', $c->get_subheader_args() ); ?>

		<?php if ( have_posts() ) : ?>
			<div class="l-container">
				<p><?php echo wp_count_posts()->publish . ' ' . __( 'posts in', 'tribe' ) . ' "' . get_the_archive_title() . '"';  ?></p>
			</div>
			<?php get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_content_loop_args() ); ?>
		<?php else :
			get_template_part( 'components/no_results/no_results', 'index' );
		endif; ?>
	</main>
<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'index',
	[ Sidebar_Controller::SIDEBAR_ID => $c->sidebar_id ]
);

get_footer();
