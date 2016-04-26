<?php
/**
 * Template Tags: Branding
 */


/**
 * Output the site logo, built for site SEO.
 *
 * @since core 1.0
 */

function the_logo() {

	printf(
		'<%1$s class="logo"><a href="%2$s" rel="home">%3$s</a></%1$s>',
		( is_front_page() ) ? 'h1' : 'div',
		esc_url( home_url() ),
		get_bloginfo( 'name' )
	);

}