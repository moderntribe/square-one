<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\document\head\Controller::factory();
?>

<head>
	<?php // TITLE: Handled by WP ?>

	<?php // MISC Meta ?>
	<meta charset="utf-8">
	<meta name="author" content="<?php echo esc_attr( $c->name() ); ?>">
	<link rel="pingback" href="<?php echo esc_url( $c->pingback_url() ); ?>">

	<?php // MOBILE META ?>
	<meta name="HandheldFriendly" content="True">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php // PLATFORM META: iOS & Android ?>
	<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( $c->page_title() ); ?>">

	<?php // PLATFORM META: IE ?>
	<meta name="application-name" content="<?php echo esc_attr( $c->name() ); ?>">

	<?php do_action( 'wp_head' ) ?>

</head>
