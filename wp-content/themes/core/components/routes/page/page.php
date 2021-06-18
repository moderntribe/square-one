<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\page\Page_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;

$c = Page_Controller::factory();

get_header();
?>

	<main id="main-content">

		<?php $c->render_breadcrumbs(); ?>

		<?php get_template_part( 'components/header/subheader/subheader' ); ?>

		<div class="s-sink t-sink l-sink">
			<?php the_content(); ?>
		</div>

	</main>

<?php
do_action( 'get_sidebar', null );
get_template_part(
	'components/sidebar/sidebar',
	'page',
	[ Sidebar_Controller::SIDEBAR_ID => $c->sidebar_id ]
);
get_footer();
