<?php declare(strict_types=1);

namespace Tribe\Project\Integrations\Google_Tag_Manager;

use Tribe\Project\Object_Meta\Analytics_Settings;

class GTM_Scripts {

	private Analytics_Settings $settings;

	public function __construct( Analytics_Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 *  Google Tag Manager (head tag)
	 *
	 * @action wp_head
	 */
	public function inject_google_tag_manager_head_tag(): void {

		$id = $this->settings->get_value( Analytics_Settings::ANALYTICS_GTM_ID );

		if ( empty( $id ) ) {
			return;
		}

		?>

		<!-- Google Tag Manager -->
		<script>(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start':
						new Date().getTime(), event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s), dl = l != 'dataLayer'?'&l=' + l:'';
				j.async = true;
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', '<?php echo $id; ?>');</script>
		<!-- End Google Tag Manager -->

		<?php
	}

	/**
	 * Google Tag Manager (body tag)
	 *
	 * @action wp_body_open
	 */
	public function inject_google_tag_manager_body_tag(): void {

		$id = $this->settings->get_value( Analytics_Settings::ANALYTICS_GTM_ID );

		if ( empty( $id ) ) {
			return;
		}

		?>

		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->

		<?php
	}

}
