<?php

$previous = get_adjacent_post( false, '', true );
$next     = get_adjacent_post( false, '', false );

// Don't print empty markup if there's nowhere to navigate.
if ( empty( $next ) && empty( $previous ) )
	return;

?>

<nav class="post-navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
	
	<h3 class="accessibility">Post Pagination</h3>
	
	<ul>

		<?php if( $previous ) { ?>
			<li class="nav-next" itemprop="url">
				<a href="<?php echo esc_attr( get_permalink( $previous->ID ) ); ?>" rel="prev">
					<?php echo $previous->post_title; ?>
				</a>
			</li>
		<?php } ?>

		<?php if( $next ) { ?>
			<li class="nav-prev" itemprop="url">
				<a href="<?php echo esc_attr( get_permalink( $next->ID ) ); ?>" rel="next">
					<?php echo $next->post_title; ?>
				</a>
			</li>
		<?php } ?>

	</ul>

</nav><!-- .post-navigation -->
