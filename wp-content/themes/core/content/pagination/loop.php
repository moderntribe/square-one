<?php

global $wp_query;

// Don't print empty markup if there's only one page.
if ( $wp_query->max_num_pages < 2 ) {
	return;
}

?>

<nav class="loop-navigation">
	
	<h3 class="visual-hide">Posts Pagination</h3>
	
	<ul>
		<?php if ( get_next_posts_link() ) : ?>
			<li class="nav-prev">
				<?php next_posts_link( '&larr; More Posts' ); ?>
			</li>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
			<li class="nav-next">
				<?php previous_posts_link( 'Previous Posts &rarr;' ); ?>
			</li>
		<?php endif; ?>
	</ul>

</nav>
