<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\routes\unsupported_browser\Unsupported_Browser_Controller;

$c = Unsupported_Browser_Controller::factory();
?>

<!DOCTYPE html>
<html>
<head>

	<title><?php echo esc_html( __( 'Unsupported Browser', 'tribe' ) ); ?> | <?php bloginfo( 'name' ); ?></title>

	<meta charset="utf-8">
	<meta name="author" content="<?php bloginfo( 'name' ); ?>">
	<meta http-equiv="cleartype" content="on">
	<meta name="robots" content="noindex, nofollow">

	<?php echo $c->get_styles(); ?>

	<link rel="shortcut icon" href="<?php echo esc_url( trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/branding-assets/favicon.ico' ); ?>">

	<?php do_action( 'tribe/unsupported_browser/head' ); ?>

</head>
<body>
<main id="main-content">

	<div class="site-header">
		<div class="l-container">
			<h1 class="site-brand">
				<img src="<?php echo $c->get_legacy_image_url( 'logo-header.png' ); ?>"
					 class="site-logo site-logo--header"
					 alt="<?php printf( '%s %s', esc_attr( get_bloginfo( 'name' ) ), esc_attr( __( 'logo', 'tribe' ) ) ); ?>" />
			</h1>
		</div>
	</div>

	<div class="site-content">
		<div class="l-container">

			<div class="site-content__content">
				<h2><?php echo esc_html( sprintf( '%s %s', __( 'Welcome to', 'tribe' ), get_bloginfo( 'name' ) ) ); ?></h2>
				<p><?php echo sprintf( '%s <a href="http://browsehappy.com/" rel="external">%s</a>.', __( 'You are viewing this site in a browser that is no longer supported or secure. For the best possible experience, we recommend that you', 'tribe' ), __( 'update or use a modern browser', 'tribe' ) ); ?></p>
			</div>

			<ul class="browser-list">
				<li class="browser-list__item">
					<a href="http://www.google.com/chrome/"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
						<span class="browser-list__item-image">
						  <img src="<?php echo $c->get_legacy_image_url( 'chrome.png' ); ?>"
							alt="<?php echo esc_attr( __( 'Chrome browser logo', 'tribe' ) ); ?>" />
						</span>
						<?php echo esc_html( __( 'Chrome', 'tribe' ) ); ?>
					</a>
				</li>
				<li class="browser-list__item">
					<a href="https://www.mozilla.org/firefox/new/"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
							   <span class="browser-list__item-image">
								   <img src="<?php echo $c->get_legacy_image_url( 'firefox.png' ); ?>"
										alt="<?php echo esc_attr( __( 'Firefox browser logo', 'tribe' ) ); ?>" />
							   </span>
						<?php echo esc_html( __( 'Firefox', 'tribe' ) ); ?>
					</a>
				</li>
				<li class="browser-list__item">
					<a href="https://support.apple.com/downloads/#safari"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
							   <span class="browser-list__item-image">
								   <img src="<?php echo $c->get_legacy_image_url( 'safari.png' ); ?>"
										alt="<?php echo esc_attr( __( 'Safari browser logo', 'tribe' ) ); ?>" />
							   </span>
						<?php echo esc_html( __( 'Safari', 'tribe' ) ); ?>
					</a>
				</li>
				<li class="browser-list__item">
					<a href="http://windows.microsoft.com/internet-explorer/download-ie"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
							   <span class="browser-list__item-image">
								   <img src="<?php echo $c->get_legacy_image_url( 'ie.png' ); ?>"
										alt=""<?php echo esc_attr( __( 'Internet Explorer browser logo', 'tribe' ) ); ?>"/>
							   </span>
						<?php echo esc_html( __( 'Internet Explorer', 'tribe' ) ); // TODO Move to Edge. No longer supporting IE11.?>
					</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="site-footer">
		<div class="l-container">

			<img src="<?php echo esc_url( $c->get_legacy_image_url( 'logo-footer.png' ) ); ?>"
				 class="site-logo site-logo--footer"
				 alt="<?php printf( '%s %s', esc_attr( get_bloginfo( 'name' ) ), esc_attr( __( 'logo', 'tribe' ) ) ); ?>" />

			<p class="site-footer__copy">
				<?php printf( __( '%s %d All Rights Reserved. %s.', 'tribe' ), '&copy;', date( 'Y' ), esc_attr( get_bloginfo( 'name' ) ) ); ?>
			</p>

		</div>
	</div>

</body>
</html>
