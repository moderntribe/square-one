<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>

	<?php // TITLE (added by theme support) ?>

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
<body <?php body_class(); ?>>

	<div id="site-wrap">

		<?php // Content: Header
		get_template_part( 'content/header/default' ); ?>
