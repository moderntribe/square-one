<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

	<nav class="pagination pagination--comments" aria-labelledby="pagination__label-comments">

		<h3 id="pagination__label-comments" class="u-visual-hide">Comments Pagination</h3>

		<ol class="pagination__list">

			<?php if ( get_previous_comments_link() ) : ?>
				<li class="pagination__item pagination__item--previous">
					<?php previous_comments_link( '&larr; Older Comments' ); ?>
				</li>
			<?php endif; ?>

			<?php if ( get_next_comments_link() ) : ?>
				<li class="pagination__item pagination__item--next">
					<?php next_comments_link( 'Newer Comments &rarr;' ); ?>
				</li>
			<?php endif; ?>

		</ol>

	</nav>

<?php } ?>
