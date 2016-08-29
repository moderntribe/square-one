<?php

global $wp_query;

// Don't print empty markup if there's only one page.
if ( $wp_query->max_num_pages < 2 ) {
	return;
}

?>

<nav class="pagination pagination--loop" aria-labelledby="pagination__label-loop">

	<h3 id="pagination__label-loop" class="u-visual-hide">Posts Pagination</h3>

	<ol class="pagination__list">

		<?php if ( get_next_posts_link() ) : ?>
			<li class="pagination__item pagination__item--next">
				<?php next_posts_link( '&larr; More Posts' ); ?>
			</li>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
			<li class="pagination__item pagination__item--previous">
				<?php previous_posts_link( 'Previous Posts &rarr;' ); ?>
			</li>
		<?php endif; ?>

	</ol>

</nav>
