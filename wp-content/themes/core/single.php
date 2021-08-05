<?php declare(strict_types=1);

while ( have_posts() ) {
	the_post();
	get_template_part( 'routes/single/single' );
}
