<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\archive\Archive_Controller;

$c = Archive_Controller::factory( $args );

$c->render_header();
?>
	<main id="main-content">
		<?php $c->render_subheader(); ?>
		
		<?php if ( have_posts() ) : ?>
			<div class="l-container"> 
				<?php get_template_part( 'components/text/text', null, $c->get_number_of_posts() );?>
			</div>
			
			<?php

			get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_loop_args() );
			
			?>
			
			<div class="l-container"> 
				<?php // to do
				// get_template_part( 'components/pagination/loop/loop', 'search' ); ?>
				
			</div>
				
		<?php else :
			get_template_part( 'components/no_results/no_results', 'index' );
		endif; ?>

	</main>
<?php
$c->render_sidebar();
$c->render_footer();
