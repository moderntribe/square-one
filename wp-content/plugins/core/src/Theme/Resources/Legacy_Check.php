<?php

namespace Tribe\Project\Theme\Resources;

class Legacy_Check {
	private $unsupported_browser_path = '';

	public function __construct( $unsupported_browser_path = '/unsupported-browser/' ) {
		$this->unsupported_browser_path = $unsupported_browser_path;
	}

	public function hook() {
		add_action( 'wp_head', [ $this, 'old_browsers' ], 0, 0 );
	}

	/**
	 * Redirect old browsers to a unique message page (IE9 and below)
	 */
	public function old_browsers() {
		?>

		<script type="text/javascript">
			function is_browser() {
				return (
					navigator.userAgent.indexOf( "Chrome" ) !== -1 ||
					navigator.userAgent.indexOf( "Opera" ) !== -1 ||
					navigator.userAgent.indexOf( "Firefox" ) !== -1 ||
					navigator.userAgent.indexOf( "MSIE" ) !== -1 ||
					navigator.userAgent.indexOf( "Safari" ) !== -1 ||
					navigator.userAgent.indexOf( "Edge" ) !== -1
				);
			}
			function not_excluded_page() {
				return (
					window.location.href.indexOf( "<?php echo $this->unsupported_browser_path; ?>" ) === -1 &&
					document.title.toLowerCase().indexOf( 'page not found' ) === -1
				);
			}
			if ( is_browser() && !window.atob && not_excluded_page() ) {
				window.location = location.protocol + '//' + location.host + '<?php echo $this->unsupported_browser_path; ?>';
			}
		</script>

		<?php
	}
}