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

			<?php // comments_template(); ?>

			<footer>
				<div class="l-container">
					<div class="s-sink t-sink l-sink l-sink--double">
						<div class="h4"><?php the_author(); ?></div>
						<p><?php the_author_meta( 'description' ); ?></p>
					</div>

					<?php get_template_part( 'components/pagination/single/single' ); ?>
				</div>
			</footer>

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
