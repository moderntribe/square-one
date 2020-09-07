<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Site_Brand;

class Login_Screen {
	/**
	 * Add Login Logo
	 *
	 * @filter login_enqueue_scripts
	 */
	public function inject_login_logo() {

		$logo_id = get_theme_mod( Customizer_Settings::SITE_BRANDING_LOGIN_LOGO );

		if ( empty( $logo_id ) ) {
			return;
		}

		$logo_data = wp_get_attachment_image_src( $logo_id, 'full' );

		if ( ! $logo_data ) {
			return;
		}

		$logo_width  = $logo_data[1] / 2 . 'px';
		$logo_height = $logo_data[2] / 2 . 'px';

		?>
		<style type="text/css">
			.login h1 a {
				background: transparent url(<?php echo esc_url( $logo_data[0] ); ?>) 50% 50% no-repeat !important;
				width: <?php esc_attr_e( $logo_width ); ?> !important;
				height: <?php esc_attr_e( $logo_height ); ?> !important;
				background-size: <?php esc_attr_e( $logo_width . ' ' . $logo_height ); ?> !important;
			}
		</style>
		<?php
	}

	/**
	 * Login Header Url
	 * @param string $url
	 *
	 * @return string
	 * @filter login_headerurl
	 */
	public function customize_login_header_url( $url ) {
		return get_home_url();
	}

	/**
	 * Login Header Title
	 * @param string $name
	 *
	 * @return string
	 * @filter login_headertext
	 */
	public function customize_login_header_title( $name ) {
		return get_bloginfo( 'name' );
	}
}
