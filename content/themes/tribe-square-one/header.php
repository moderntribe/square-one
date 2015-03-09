<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>

	<?php // TITLE ?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<?php // MISC META ?>
	<meta charset="utf-8">
	<meta name="author" content="<?php bloginfo( 'name' ); ?>">
	<meta http-equiv="cleartype" content="on">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php // MOBILE META ?>
	<meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<?php // PLATFORM META: iOS & Android ?>
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( get_the_title() ); ?>">

	<?php // PLATFORM META: IE ?>
	<meta name="application-name" content="<?php bloginfo( 'name' ); ?>">
	<meta name="msapplication-TileColor" content="#333">

	<?php // FAVICON & ICONS (handled in tribe-core plugin) ?>

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<div id="site-wrap">

		<header role="banner" itemscope itemtype="http://schema.org/WPHeader">
			<div class="content-wrap">

				<?php the_logo(); ?>

				<nav role="navigation" aria-label="Main Navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<h3 class="accessibility">Menu</h3>
					<ol>
						<?php  // Header Nav
						$defaults = array(
							'theme_location' => 'primary',
							'container' => false,
							'container_class' => '',
							'menu_class' => '',
							'menu_id' => '',
							'items_wrap' => '%3$s',
							'fallback_cb' => false );
						wp_nav_menu( $defaults ); ?>
					</ol>
				</nav><!-- nav -->

			</div><!-- .content-wrap -->
		</header><!-- header -->
