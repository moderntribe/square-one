<?php
/**
 * Template Name: Page - Unsupported Browser
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<?php // TITLE ?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<?php // MISC META ?>
	<meta charset="utf-8">
	<meta name="author" content="<?php bloginfo( 'name' ); ?>">
	<meta http-equiv="cleartype" content="on">
	<meta name="robots" content="noindex, nofollow">

	<?php // CSS
	$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/';
	$css_legacy = ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) ? 'dist/legacy.min.css' : 'legacy.css';
	?>
	<link rel="stylesheet" href="<?php echo esc_url( $css_dir . $css_legacy ); ?>" type="text/css" media="all">

	<?php // FAVICON ?>
	<link rel="shortcut icon" href="<?php echo trailingslashit( get_template_directory_uri() ); ?>img/branding/favicon.ico">

	<?php // FONTS
	$fonts = new \Tribe\Project\Theme\Resources\Fonts();
	$fonts->load_fonts(); ?>

</head>
<body class="page-legacy" itemscope itemtype="https://schema.org/WebPage">

<div class="l-site-wrapper">

	<div class="header">
		<div class="l-wrapper">
			<h1>
				<img src="<?php echo trailingslashit( get_template_directory_uri() ); ?>img/logos/logo-legacy.png" alt="<?php bloginfo( 'name' ); ?>" />
				<span class="visual-hide"><?php bloginfo( 'name' ); ?></span>
			</h1>
		</div>
	</div>

	<div class="main" itemprop="mainContentOfPage">
		<div class="l-wrapper">

			<div itemprop="text">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>

				<ul class="browsers">
					<li><a href="https://www.mozilla.org/en-US/firefox/new/" class="firefox" rel="external" target="_blank"><?php esc_attr_e( 'Firefox', 'tribe' ); ?></a></li>
					<li><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" class="ie" rel="external" target="_blank"><?php esc_attr_e( 'IE 9+', 'tribe' ); ?></a></li>
					<li><a href="http://www.google.com/chrome/" class="chrome" rel="external" target="_blank"><?php esc_attr_e( 'Chrome', 'tribe' ); ?></a></li>
					<li><a href="https://support.apple.com/downloads/#safari" class="safari" rel="external" target="_blank"><?php esc_attr_e( 'Safari 6+', 'tribe' ); ?></a></li>
				</ul>

			</div>

		</div>
	</div>

</div>

</body>
</html>
