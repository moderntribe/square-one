<?php
/**
 * Template Tags: Pagination
 */


/**
 * Display navigation to next/previous post when applicable.
 *
 * @since tribe-square-one 1.0
 * @return void
 */

if ( ! function_exists( 'tribe_post_nav' ) ) :

	function tribe_post_nav() {

		// Don't print empty markup if there's nowhere to navigate.
		$previous = get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( empty( $next ) && empty( $previous ) )
			return;

	?>

		<nav class="post-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
			
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

	<?php 
	}

endif;


/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since tribe-square-one 1.0
 * @return void
 */

if ( ! function_exists( 'tribe_paging_nav' ) ) :

	function tribe_paging_nav() {

		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

	?>

		<nav class="loop-navigation" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
			
			<h3 class="accessibility">Posts Pagination</h3>
			
			<ul>
				<?php if ( get_next_posts_link() ) : ?>
					<li class="nav-prev" itemprop="url"><?php next_posts_link( '&larr; More Posts' ); ?></li>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
					<li class="nav-next" itemprop="url"><?php previous_posts_link( 'Previous Posts &rarr;' ); ?></li>
				<?php endif; ?>
			</ul>
		
		</nav><!-- .loop-navigation -->

	<?php
	}

endif;

