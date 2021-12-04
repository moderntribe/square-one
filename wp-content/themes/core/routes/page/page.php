<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\section_nav\Section_Nav_Controller;
use Tribe\Project\Templates\Components\sidebar\Sidebar_Controller;
use Tribe\Project\Templates\Routes\page\Page_Controller;

$c = Page_Controller::factory();

get_header();
?>

	<main id="main-content">

		<?php get_template_part( 'components/header/subheader/subheader', null, $c->get_subheader_args() ); ?>

		<?php // TODO: Remove this after dev. ?>
		<div class="l-container">
			<?php get_template_part( 'components/section_nav/section_nav', null, [
				Section_Nav_Controller::MENU => 51,
			] ); ?>
		</div>
		<div class="l-container">
			<?php get_template_part( 'components/section_nav/section_nav', null, [
				Section_Nav_Controller::MENU => 52,
			] ); ?>
		</div>

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
