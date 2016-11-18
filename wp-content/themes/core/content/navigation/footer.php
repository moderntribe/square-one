<?php if( has_nav_menu( 'nav-secondary' ) ) { ?>

	<nav class="nav-secondary" aria-labelledby="nav-secondary__label">

		<h2 id="nav-secondary__label" class="u-visual-hide"><?php _e( 'Secondary Navigation', 'tribe' ); ?></h2>

		<ol class="nav-secondary__list">
			<?php
			$defaults = array(
				'theme_location'  => 'nav-secondary',
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
