<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\archive\Archive_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

$c = Archive_Controller::factory();

get_header();
?>
	<main id="main-content">
		<?php get_template_part( 'components/header/subheader_archive/subheader_archive', 'index' ); ?>

		<?php if ( have_posts() ) : ?>
			<div class="l-container">
				<p><?php echo wp_count_posts()->publish . ' ' . __( 'posts in', 'tribe' ) . ' "' . get_the_archive_title() . '"';  ?></p>
			</div>
			<?php get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_loop_args() ); ?>
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
