<?php

namespace Tribe\Project\Assets\Theme;

class Legacy_Check {

	/** @var string */
	private $unsupported_browser_path;

	public function __construct( $unsupported_browser_path = '/unsupported-browser/' ) {
		$this->unsupported_browser_path = $unsupported_browser_path;
	}

	/**
	 * Redirect old browsers to a unique message page (IE9 and below)
	 *
	 * @action wp_head
	 */
	public function print_redirect_script(): void {
		?>

		<script>
			function is_browser() {
				return (navigator.userAgent.indexOf("Chrome") !== - 1 || navigator.userAgent.indexOf("Opera") !== - 1 || navigator.userAgent.indexOf("Firefox") !== - 1 || navigator.userAgent.indexOf("MSIE") !== - 1 || navigator.userAgent.indexOf("Safari") !== - 1 || navigator.userAgent.indexOf("Edge") !== - 1);
			}

			function less_than_ie11() {
				return (!window.atob || // IE9 and below
						Function('/*@cc_on return document.documentMode===10@*/')() // IE10
				);
			}

			function not_excluded_page() {
				return (window.location.href.indexOf("<?php echo $this->unsupported_browser_path; ?>") === - 1 && document.title.toLowerCase().indexOf('page not found') === - 1);
			}

			if (is_browser() && less_than_ie11() && not_excluded_page()) {
				window.location = location.protocol + '//' + location.host + '<?php echo $this->unsupported_browser_path; ?>';
			}
		</script>

		<?php
	}

	/**
	 * Adds the rewrite rule for the unsupported-browser permalink.
	 *
	 * @action init
	 */
	public function add_unsupported_rewrite(): void {
		add_rewrite_tag( '%unsupported%', '([^&]+)' );
		$regex = sprintf( '^%s/?', str_replace( '/', '', $this->unsupported_browser_path ) );
		add_rewrite_rule( $regex, 'index.php?unsupported=1', 'top' );
	}

	/**
	 * Loads the unsupported browser template
	 *
	 * @param string $template The template file to load.
	 *
	 * @return mixed
	 * @filter template_include
	 */
	public function load_unsupported_template( $template ) {

		global $wp_query;

		if ( ! array_key_exists( 'unsupported', $wp_query->query ) || '1' != $wp_query->query['unsupported'] ) {
			return $template;
		}

		get_template_part( 'components/routes/unsupported_browser/unsupported_browser' );
		exit;
	}
}
