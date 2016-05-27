<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>

	<nav class="comment-navigation">
		
		<h6 class="accessbility">Comments Navigation</h6>

		<div class="nav-prev">
			<?php previous_comments_link( '&larr; Older Comments' ); ?>
		</div>

		<div class="nav-next">
			<?php next_comments_link( 'Newer Comments &rarr;' ); ?>
		</div>

	</nav>

<?php } ?>
