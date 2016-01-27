<?php // Menu: Footer
if( has_nav_menu( 'footer' ) ) { ?>

	<nav aria-label="Secondary Navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">

		<h5 class="accessibility">Secondary Navigation</h5>

		<ol>
			<?php
			$defaults = array(
				'theme_location'  => 'footer',
				'container'       => false,
				'container_class' => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'depth'           => 1,
				'items_wrap'      => '%3$s',
				'walker'          => new Tribe_Walker_Nav_Menu,
				'fallback_cb'     => false
			);
			wp_nav_menu( $defaults ); ?>
		</ol>

	</nav><!-- nav -->

<?php } ?>