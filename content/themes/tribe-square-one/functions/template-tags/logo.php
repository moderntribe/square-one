<?php
/**
 * Template Tags: Logo
 *
 * @since tribe-square-one 1.0)
 */


/**
 * Output the site logo, built for site SEO.
 *
 * @since tribe-square-one 1.0
 */

function the_logo( $mobile = false ) { 

	$logo = ( $mobile ) ? 'logo-small.png' : 'logo.png';
	$logo_size = ( $mobile ) ? ' logo-mobile' : ' logo-full';
	
?>
		
	<div class="logo<?php echo $logo_size; ?>" itemscope itemprop="author headline" itemtype="http://schema.org/Organization">
		
		<?php if( is_front_page() ) { ?>
			<h1 class="logo-wrap">
		<?php } ?>
		
		<a href="<?php echo esc_url( home_url() ); ?>" class="logo-wrap" rel="home" itemprop="url">

			<img src="<?php echo trailingslashit( get_template_directory_uri() ); ?>img/logos/<?php echo $logo; ?>" 
		         alt="Square One" 
		         itemprop="logo" />

		</a>
		
		<?php if( is_front_page() ) { ?>
			</h1>
		<?php } ?>
	
	</div><!-- .logo -->

<?php 

}

