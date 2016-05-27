<!DOCTYPE html>
<html lang="en">
<head>

	<?php // TITLE ?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<?php // MISC META ?>
	<meta charset="utf-8">
	<meta name="author" content="Stanford 125">
	<meta http-equiv="cleartype" content="on">
	<meta name="robots" content="noindex, nofollow">

	<?php // CSS
	$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/';
	$css_legacy = ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) ? 'dist/legacy.min.css' : 'legacy.css';
	?>
	<link rel="stylesheet" href="<?php echo $css_dir . $css_legacy; ?>" type="text/css" media="all">

	<?php // FAVICON ?>
	<link rel="shortcut icon" href="<?php echo trailingslashit( get_template_directory_uri() ); ?>img/branding/favicon.ico">

	<?php // FONTS
	core_fonts(); ?>

</head>
<body class="page-legacy" itemscope itemtype="https://schema.org/WebPage">

<div class="site-wrap">

	<div class="header">
		<div class="content-wrap">
			<h1>
				<img src="<?php echo trailingslashit( get_template_directory_uri() ); ?>img/logos/logo-legacy.png" alt="<?php bloginfo( 'name' ); ?>" />
				<span class="visual-hide"><?php bloginfo( 'name' ); ?></span>
			</h1>
		</div><!-- .content-wrap -->
	</div><!-- .header -->

	<div class="main" itemprop="mainContentOfPage">
		<div class="content-wrap">

			<div itemprop="text">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>

				<ul class="browsers">
					<li><a href="https://www.mozilla.org/en-US/firefox/new/" class="firefox" rel="external" target="_blank">Firefox</a></li>
					<li><a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" class="ie" rel="external" target="_blank">IE 9+</a></li>
					<li><a href="http://www.google.com/chrome/" class="chrome" rel="external" target="_blank">Chrome</a></li>
					<li><a href="https://support.apple.com/downloads/#safari" class="safari" rel="external" target="_blank">Safari 6+</a></li>
				</ul>

			</div>

		</div><!-- .content-wrap -->
	</div><!-- .main -->

</div><!-- .site-wrap -->

</body>
</html>
