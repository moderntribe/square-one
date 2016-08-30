<?php if( has_nav_menu( 'primary' ) ) { ?>

	<nav class="site-header__nav" aria-labelledby="site-header__nav-label">

		<h2 id="site-header__nav-label" class="u-visual-hide">Primary Navigation</h2>

		<ol class="site-header__nav-list">
			<?php // Header Nav
			$defaults = array(
				'theme_location'  => 'primary',
				'container'       => false,
				'container_class' => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'depth'           => 3,
				'items_wrap'      => '%3$s',
				'fallback_cb'     => false
			);
			\Tribe\Project\Theme\Nav\Menu::menu( $defaults );
			?>
		</ol>

	</nav>

<?php } ?>
