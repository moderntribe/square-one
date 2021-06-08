<?php declare(strict_types=1);

use \Tribe\Project\Templates\Components\routes\index\Index_Controller;

$c = Index_Controller::factory( $args );

$c->render_header();
?>
	<main id="main-content">
		<?php $c->render_subheader(); ?>
		
		<?php if (  is_home() ) : ?>
			<?php the_content(); ?>

		<?php else :
			if ( have_posts() ) : ?>
				<div class="l-container"> 
					<?php get_template_part( 'components/text/text', null, $c->get_number_of_posts() );?>
				</div>
				
				<?php

				if ( $c->get_current_page() === 1 && ! empty( $c->get_featured_posts_args() ) ) :
					get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_featured_posts_args() );
				endif;

				get_template_part( 'components/blocks/content_loop/content_loop', null, $c->get_loop_args() );
				
				?>
				
				<div class="l-container"> 
					<?php //get_template_part( 'components/pagination/loop/loop', 'index' ); ?>
				</div>
				
			<?php else :
				get_template_part( 'components/no_results/no_results', 'index' );
			endif; 
		endif; ?>

	</main>
<?php
$c->render_sidebar();
$c->render_footer();
