<header class="site-header" role="banner">

	<div class="content-wrap">

		<?php // Logo
		the_logo(); ?>

		<?php // Menu: Primary
		get_template_part( 'content/navigation/header' ); ?>

		<?php // Search: Form
		get_template_part( 'content/search/searchform' ); ?>

	</div><!-- .content-wrap -->

</header><!-- .site-header -->