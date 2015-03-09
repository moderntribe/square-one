<?php

/**
 * Tribe Branding
 *
 * Update links and logos and social icons
 *
 * @todo      Make a global override admin for this on MS installs.
 * @todo      Support ogtags, twitter, google authors(?)... Anything else?
 * @version   1.0
 * @copyright Modern Tribe, Inc. 2013
 */
class Tribe_Branding {


	/********** CORE FUNCTIONS **********/

	/**
	 * Class Constructor
	 *
	 * Enqueue scripts and init filters.
	 */
	protected function __construct() {}

	/**
	 * Enqueue scripts and init filters.
	 */
	protected function add_hooks() {
		//add_action( 'login_head', array( $this, 'set_login_logo' ) );
		add_filter( 'login_headerurl', array( $this, 'set_login_header_url' ) );
		add_filter( 'login_headertitle', array( $this, 'set_login_header_title' ) );
		add_action( 'wp_head', array( $this, 'set_favicon' ) );
		add_action( 'wp_head', array( $this, 'set_ios_icon' ), 10, 0 );
		add_action( 'wp_head', array( $this, 'set_ie_metro_icon' ), 10, 0 );
		//add_action( 'wp_head', array( $this, 'set_social_meta' ), 10, 0 );
		add_action( 'customize_register', array( $this, 'theme_customizer' ) );
		add_filter( 'upload_mimes', array( $this, 'support_ico_uploads' ) );
		add_action( 'wp_footer', array( $this, 'tribe_attribute_credit' ), 999 );
		add_filter( 'admin_footer_text', array( $this, 'edit_admin_footer_text' ), 10, 1 );
	}

	/**
	 * Login Logo
	 */
	public function set_login_logo() {
		$image = plugins_url( 'assets/logo_admin.png', dirname( __FILE__ ) );
		$image = apply_filters( 'branding_login_logo', $image );
		?>
		<style type="text/css">
			.login h1 {
				margin : 0 -60px;
			}

			.login h1 a {
				background      : transparent url(<?php echo $image; ?>) no-repeat scroll center center;
				background-size : 320px 57px;
				width           : auto;
			}
		</style>
	<?php
	}

	/**
	 * Login Header Url
	 */
	public function set_login_header_url( $url = '' ) {
		return get_home_url();
	}

	/**
	 * Login Header Title
	 */
	public function set_login_header_title( $name = '' ) {
		return get_bloginfo( 'name' );
	}

	/**
	 * Favicon
	 */
	public function set_favicon() {
		$favicon = get_theme_mod( 'branding_customizer_icon_favicon' );
		if ( empty( $favicon ) ) {
			$favicon = plugins_url( 'assets/favicon.ico', dirname( __FILE__ ) );
		}
		$favicon = apply_filters( 'branding_favicon', $favicon );
		echo '<link rel="shortcut icon" href="' . $favicon . '">';
	}

	/**
	 * iOS Icon
	 */
	public function set_ios_icon() {
		$icon = get_theme_mod( 'branding_customizer_icon_ios' );
		if ( ! empty( $icon ) )
			echo '<link rel="apple-touch-icon-precomposed" href="' . $icon . '">';
	}

	/**
	 * IE Metro Icon
	 */
	public function set_ie_metro_icon() {
		$icon = get_theme_mod( 'branding_customizer_icon_ie' );
		if ( ! empty( $icon ) )
			echo '<meta name="msapplication-TileImage" content="' . $icon . '">';
	}

	/**
	 * Set meta for the page thumbnail for social sharing
	 * @return void
	 */
	public function set_social_meta() {
		if ( is_singular() ) {
			if ( has_post_thumbnail() ) {
				$attachment_id = get_post_thumbnail_id();
			}
			if ( empty( $attachment_id ) && class_exists( 'MultiPostThumbnails' ) ) {
				$attachment_id = MultiPostThumbnails::get_post_thumbnail_id( get_post_type(), 'small-thumbnail', get_the_ID() );
			}
			if ( !empty( $attachment_id ) ) {
				$image = wp_get_attachment_image_src( $attachment_id, 'medium' );
				if ( $image ) {
					$image = $image[0];
				}
			}
		}
		if ( empty( $image ) ) {
			$image = tribe_get_logo();
		}
		$image = apply_filters( 'tribe_social_image', $image );
		if ( ! empty( $image ) ) {
			printf( "\n" . '<meta name="twitter:image" content="%s" />', apply_filters( 'tribe_branding_twitter_image', $image ) );
			printf( "\n" . '<meta name="og:image" content="%s" />', apply_filters( 'tribe_branding_og_image', $image ) );
			echo "\n";
		}
	}

	/**
	 * @return string URL to the logo
	 */
	public function get_logo() {
		// only do this once per page load
		static $logo = FALSE;
		if ( $logo !== FALSE ) {
			return $logo;
		}

		// If a logo was cached, use it
		//$logo = Tribe_ObjectCache::get('logo', 'tribe');
		//if ( !empty($logo) ) return $logo;

		// If logo is uploaded, use it
		$logo = get_theme_mod('tribe_theme_customizer_logo');
		if ( !empty($logo) ) {
			//Tribe_ObjectCache::set('logo', -1, 'tribe');
			return $logo;
		}

		// Fall back on hardcoded logo.
		// todo: write fallback code here to look in theme branding folder and fall back on plugin assets/branding
		return '';
	}


	/**
	 * Add an App Icons section to the Theme Customizer
	 *
	 * @param $wp_customize
	 *
	 * @todo add toll tips etc to help user understand sizes etc.
	 */
	public function theme_customizer( $wp_customize ) {

		$wp_customize->add_section( 'branding_customizer', array(
			'title'    => apply_filters( 'branding_customizer_title', __( 'Branding', 'tribe' ) ),
			'priority' => apply_filters( 'branding_customizer_priority', 500 )
		) );

		// Fav Icon
		$wp_customize->add_setting( 'branding_customizer_icon_favicon' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_favicon', array(
			'label'      => __( 'Favicon (64x64 ICO)', 'tribe' ),
			'section'    => 'branding_customizer',
			'settings'   => 'branding_customizer_icon_favicon',
			'extensions' => array( 'ico' ),
		) ) );

		// iOS Icon
		$wp_customize->add_setting( 'branding_customizer_icon_ios' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_ios', array(
			'label'      => __( 'iOS Icon (512x512 PNG)', 'tribe' ),
			'section'    => 'branding_customizer',
			'settings'   => 'branding_customizer_icon_ios'
		) ) );

		// IE Metro Icon
		$wp_customize->add_setting( 'branding_customizer_icon_ie' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_ie', array(
			'label'      => __( 'IE Metro Icon (144x144 PNG)', 'tribe' ),
			'section'    => 'branding_customizer',
			'settings'   => 'branding_customizer_icon_ie'
		) ) );

	}

	/**
	 * Support ico file uploads
	 *
	 * @param array $existing_mimes
	 *
	 * @return array
	 */
	function support_ico_uploads( $existing_mimes = array() ) {
		// Add file extension 'extension' with mime type 'mime/type'
		$existing_mimes['ico'] = 'image/x-icon';

		return $existing_mimes;
	}

	/**
	 * Tribe attribution credit in an HTML comment.
	 */
	public function tribe_attribute_credit() {
		echo "\n<!-- Hand crafted by Modern Tribe, Inc. (http://tri.be) -->\n\n";
	}

	/**
	 * Edit the admin footer text
	 */
	public function edit_admin_footer_text() {
		echo get_bloginfo( 'name' ) .' running on <a href="http://wordpress.org/">WordPress</a>';
	}

	/********** SINGLETON FUNCTIONS **********/

	/* Don't edit below here! */

	/**
	 * Instance of this class for use as singleton
	 */
	private static $instance;

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		self::$instance = self::get_instance();
		self::$instance->add_hooks();
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @static
	 * @return Tribe_Branding
	 */
	public static function get_instance() {
		if ( ! is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

