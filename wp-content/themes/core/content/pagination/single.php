<?php

$previous = get_adjacent_post( false, '', true );
$next     = get_adjacent_post( false, '', false );

// Don't print empty markup if there's nowhere to navigate.
if ( empty( $next ) && empty( $previous ) ) {
	return;
}

?>

<nav class="pagination pagination--single" aria-labelledby="pagination__label-single">

	<h3 id="pagination__label-single" class="visual-hide">Post Pagination</h3>

	<ol class="pagination__list">

		<?php if ( $previous ) : ?>
			<li class="pagination__item pagination__item--next">
				<a href="<?php echo esc_attr( get_permalink( $previous->ID ) ); ?>" rel="prev">
					<?php echo $previous->post_title; ?>
				</a>
			</li>
		<?php endif; ?>

		<?php if ( $next ) : ?>
			<li class="pagination__item pagination__item--previous">
				<a href="<?php echo esc_attr( get_permalink( $next->ID ) ); ?>" rel="next">
					<?php echo $next->post_title; ?>
				</a>
			</li>
		<?php endif; ?>

	</ol>

</nav>
