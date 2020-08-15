<?php
declare( strict_types=1 );
$c = \Tribe\Project\Templates\Components\routes\unsupported_browser\Controller::factory();
?>
<!DOCTYPE html>
<html>
<head>

	<title><?php echo esc_attr( __( 'Unsupported Browser', 'tribe' ) ); ?>
		| <?php echo esc_html( $c->name ); ?></title>

	<meta charset="utf-8">
	<meta name="author" content="{{ name|esc_attr }}">
	<meta http-equiv="cleartype" content="on">
	<meta name="robots" content="noindex, nofollow">

	<?php echo $c->styles; ?>

	<link rel="shortcut icon" href="<?php esc_url( $c->favicon ); ?>">

	<?php do_action( 'tribe/unsupported_browser/head' ); ?>

</head>
<body>
<main id="main-content">

	<div class="site-header">
		<div class="l-container">
			<h1 class="site-brand">
				<img src="<?php echo $c->legacy_logo_header; ?>"
					 class="site-logo site-logo--header"
					 alt="<?php printf( '%s %s', esc_attr( $c->name ), esc_attr( __( 'logo', 'tribe' ) ) ); ?>" />
			</h1>
		</div>
	</div>

	<div class="site-content">
		<div class="l-container">

			<div class="site-content__content">
				<h2><?php echo esc_html( $c->legacy_browser_title ); ?></h2>
				<p><?php echo $c->legacy_browser_content; ?></p>
			</div>

			<ul class="browser-list">
				<li class="browser-list__item">
					<a href="http://www.google.com/chrome/"
					   class="browser-list__item-anchor"
					   rel="external noopener"
					   target="_blank">
						<span class="browser-list__item-image">
						  <img src="<?php echo $c->legacy_browser_icon_chrome; ?>"
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
								   <img src="<?php echo esc_url( $c->legacy_browser_icon_firefox ); ?>"
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
								   <img src="<?php echo esc_url( $c->legacy_browser_icon_safari ); ?>"
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
								   <img src="<?php echo esc_url( $c->legacy_browser_icon_ie ); ?>"
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

			<img src="<?php echo esc_url( $c->legacy_logo_footer ); ?>"
				 class="site-logo site-logo--footer"
				 alt="<?php printf( '%s %s', esc_attr( $c->name ), esc_attr( __( 'logo', 'tribe' ) ) ); ?>" />

			<p class="site-footer__copy">
				<?php printf( __( '%s %d All Rights Reserved. %s.', 'tribe' ), '&copy;', date( 'Y' ), esc_attr( $c->name ) ); ?>
			</p>

		</div>
	</div>

</body>
</html>
