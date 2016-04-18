<!DOCTYPE html>
<html lang="en">
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

	<?php // FAVICON & ICONS (handled in tribe-branding plugin) ?>

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

	<div id="site-wrap">

		<?php // Content: Header
		get_template_part( 'content/header/default' ); ?>
