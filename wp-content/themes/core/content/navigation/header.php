<?php // Menu: Primary
use Tribe\Project\Nav\Walker\Core_Walker_Nav_Menu;

if( has_nav_menu( 'primary' ) ) { ?>

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
				'walker'          => new Core_Walker_Nav_Menu,
				'fallback_cb'     => false
			);
			wp_nav_menu( $defaults ); ?>
		</ol>

	</nav>

<?php } ?>
