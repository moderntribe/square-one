<?php if( has_nav_menu( 'primary' ) ) { ?>

	<nav>

		<h5 class="visual-hide">Primary Navigation</h5>

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
				'fallback_cb'     => false
			);
			\Tribe\Project\Theme\Nav\Menu::menu( $defaults );
			?>
		</ol>

	</nav>

<?php } ?>
