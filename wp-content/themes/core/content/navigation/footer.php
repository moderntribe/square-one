<?php if( has_nav_menu( 'secondary' ) ) { ?>

	<nav>

		<h5 class="visual-hide">Secondary Navigation</h5>

		<ol>
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
