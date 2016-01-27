<?php // Menu: Primary
if( has_nav_menu( 'primary' ) ) { ?>

	<nav aria-label="Primary Navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">

		<h5 class="accessibility">Primary Navigation</h5>

		<ol>
			<?php // Header Nav
			$defaults = array(
				'theme_location'  => 'primary',
				'container'       => false,
				'container_class' => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'depth'           => 3,
				'items_wrap'      => '%3$s',
				'walker'          => new Tribe_Walker_Nav_Menu,
				'fallback_cb'     => false
			);
			wp_nav_menu( $defaults ); ?>
		</ol>

	</nav><!-- nav -->

<?php } ?>