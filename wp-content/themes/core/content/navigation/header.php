<?php
use Tribe\Project\Theme\Nav;
if( has_nav_menu( 'nav-primary' ) ) { ?>

	<nav class="nav-primary" aria-labelledby="nav-primary__label">

		<h2 id="nav-primary__label" class="u-visual-hide"><?php _e( 'Primary Navigation', 'tribe' ); ?></h2>

		<ol class="nav-primary__list">
			<?php // Header Nav
			$defaults = array(
				'theme_location'  => 'nav-primary',
				'container'       => false,
				'container_class' => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'depth'           => 3,
				'items_wrap'      => '%3$s',
				'walker'          => new Nav\Walker_Nav_Menu_Primary,
				'fallback_cb'     => false
			);
			Nav\Menu::menu( $defaults );
			?>
		</ol>

	</nav>

<?php } ?>
