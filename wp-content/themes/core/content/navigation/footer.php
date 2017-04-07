<?php if( has_nav_menu( 'secondary' ) ) { ?>

	<nav class="site-footer__nav" aria-labelledby="site-footer__nav-label">

		<h2 id="site-footer__nav-label" class="u-visual-hide"><?php _e( 'Secondary Navigation', 'tribe' ); ?></h2>

		<ol class="site-footer__nav-list">
			<?php
			$defaults = array(
				'theme_location'  => 'secondary',
				'container'       => false,
				'container_class' => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'depth'           => 1,
				'items_wrap'      => '%3$s',
				'fallback_cb'     => false
			);
			\Tribe\Project\Theme\Nav\Menu::menu( $defaults ); ?>
		</ol>

	</nav>

<?php } ?>
