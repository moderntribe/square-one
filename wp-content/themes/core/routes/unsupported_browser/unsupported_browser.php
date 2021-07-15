<?php declare(strict_types=1);

use \Tribe\Project\Templates\Routes\unsupported_browser\Unsupported_Browser_Controller;

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
			<div class="g-row g-row--col-1">
				<div class="g-col site-header--underline">
					<h1 class="site-brand">
						<?php
						if ( $c->get_logo_url() ) : ?>
						<img src="<?php echo $c->get_logo_url(); ?>"
							 class="site-logo site-logo--header"
							 alt="<?php printf( '%s %s', esc_attr( get_bloginfo( 'name' ) ), esc_attr( __( 'logo', 'tribe' ) ) ); ?>" />
							<?php
						else :
							esc_attr_e( get_bloginfo( 'name' ) );
						endif;
						?>

					</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="site-content">
		<div class="l-container">

			<div class="site-content__content">
				<h2><?php esc_html_e( 'Folks, time to get a new browser', 'tribe' ); ?></h2>
				<p><?php echo sprintf( '%s <a href="http://browsehappy.com/" rel="external">%s</a>.', __( 'You are viewing this site in a browser that is no longer supported or secure. For the best possible experience, we recommend that you', 'tribe' ), __( 'update or use a modern browser', 'tribe' ) ); ?></p>
			</div>

			<ul class="browser-list">
				<li class="browser-list__item">
					<a href="http://www.google.com/chrome/"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
						<span class="browser-list__item-image">
						  <img src="<?php echo $c->get_legacy_image_url( 'chrome.svg' ); ?>"
							alt="<?php echo esc_attr( __( 'Chrome browser logo', 'tribe' ) ); ?>" />
							<br/>
							<?php echo esc_html( __( 'Chrome', 'tribe' ) ); ?>
						</span>

					</a>
				</li>
				<li class="browser-list__item">
					<a href="https://www.mozilla.org/firefox/new/"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
							   <span class="browser-list__item-image">
								   <img src="<?php echo $c->get_legacy_image_url( 'firefox.svg' ); ?>"
										alt="<?php echo esc_attr( __( 'Firefox browser logo', 'tribe' ) ); ?>" />
									<br/>
									<?php echo esc_html( __( 'Firefox', 'tribe' ) ); ?>
							   </span>

					</a>
				</li>
				<li class="browser-list__item">
					<a href="https://support.apple.com/downloads/#safari"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
							   <span class="browser-list__item-image">
								   <img src="<?php echo $c->get_legacy_image_url( 'safari.svg' ); ?>"
										alt="<?php echo esc_attr( __( 'Safari browser logo', 'tribe' ) ); ?>" />
									<br/>
								   <?php echo esc_html( __( 'Safari', 'tribe' ) ); ?>
								</span>

					</a>
				</li>
				<li class="browser-list__item">
					<a href="https://www.microsoft.com/en-us/edge"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
							   <span class="browser-list__item-image">
								   <img src="<?php echo $c->get_legacy_image_url( 'edge.svg' ); ?>"
										alt=""<?php echo esc_attr( __( 'Edge browser logo', 'tribe' ) ); ?>"/>
									<br/>
								   <?php echo esc_html( __( 'Edge', 'tribe' ) ); // TODO Move to Edge. No longer supporting IE11.?>
							   </span>

					</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="site-footer">
		<div class="l-container">
			<div class="g-row g-row--col-1">
				<div class="site-footer--overline">
					<p class="site-footer__copy">
						<?php printf(
							__( '%s %d All Rights Reserved. %s.', 'tribe' ),
							'&copy;',
							date( 'Y' ),
							esc_attr( get_bloginfo( 'name' ) )
						); ?>
					</p>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
