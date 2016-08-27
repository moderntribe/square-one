<?php
/**
 * Tribe Branding
 *
 * Update links and logos and social icons
 *
 * @version   2.0
 * @copyright Modern Tribe, Inc.
 */

class Tribe_Branding {

	/********** CORE FUNCTIONS **********/

	/**
	 * Class Constructor
	 *
	 * Enqueue scripts and init filters.
	 */
	protected function __construct() {
	}

	/**
	 * Enqueue scripts and init filters.
	 */
	protected function add_hooks() {

		add_action( 'login_enqueue_scripts', array( $this, 'set_login_logo' ) );
		add_filter( 'login_headerurl', array( $this, 'set_login_header_url' ) );
		add_filter( 'login_headertitle', array( $this, 'set_login_header_title' ) );
		add_action( 'wp_head', array( $this, 'set_favicon' ) );
		add_action( 'login_head', array( $this, 'set_favicon' ) );
		add_action( 'admin_head', array( $this, 'set_favicon' ) );
		add_action( 'wp_head', array( $this, 'set_android_icon' ), 10, 0 );
		add_action( 'wp_head', array( $this, 'set_ios_icon' ), 10, 0 );
		add_action( 'wp_head', array( $this, 'set_ie_metro_icon' ), 10, 0 );
		add_action( 'wp_head', array( $this, 'set_ie_metro_icon_bgd_color' ), 10, 0 );
		add_action( 'wp_head', array( $this, 'set_android_theme_color' ), 10, 0 );
		add_action( 'customize_register', array( $this, 'theme_customizer' ), 20 );
		add_filter( 'upload_mimes', array( $this, 'support_ico_uploads' ) );
		add_action( 'wp_footer', array( $this, 'tribe_attribute_credit' ), 999 );
		add_filter( 'admin_footer_text', array( $this, 'edit_admin_footer_text' ), 10, 1 );
	}

	/**
	 * Login Logo
	 */
	public function set_login_logo() {

		$logo = get_theme_mod( 'branding_customizer_icon_admin',
			get_stylesheet_directory_uri() . '/img/logos/logo.png' );
		if ( empty( $logo ) || ( file_exists( $logo ) === false ) ) {
			return;
		}
		$logo_dimensions = getimagesize( $logo );
		$ld_width        = $logo_dimensions[0] / 2 . 'px';
		$ld_height       = $logo_dimensions[1] / 2 . 'px';
		?>
		<style type="text/css">
			.login h1 a {
				background: transparent url(<?php echo $logo; ?>) 50% 50% no-repeat !important;
				width: <?php echo $ld_width; ?> !important;
				height: <?php echo $ld_height; ?> !important;
				background-size: <?php echo $ld_width . ' ' . $ld_height; ?> !important;
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

		$icon = get_theme_mod( 'branding_customizer_icon_favicon', get_stylesheet_directory_uri() . '/img/branding/favicon.ico' );
		echo '<link rel="shortcut icon" href="' . $icon . '">';
	}

	/**
	 * Android Icon
	 */
	public function set_android_icon() {

		$icon = get_theme_mod( 'branding_customizer_icon_android', get_stylesheet_directory_uri() . '/img/branding/android-icon.png' );
		echo '<link rel="icon" sizes="192x192" href="' . $icon . '">';
	}

	/**
	 * iOS Icon
	 */
	public function set_ios_icon() {

		$icon = get_theme_mod( 'branding_customizer_icon_ios', get_template_directory_uri() . '/img/branding/apple-touch-icon-precomposed.png' );
		echo '<link rel="apple-touch-icon-precomposed" href="' . $icon . '">';
	}

	/**
	 * IE Metro Icon
	 */
	public function set_ie_metro_icon() {

		$icon = get_theme_mod( 'branding_customizer_icon_ie', get_template_directory_uri() . '/img/branding/ms-icon-144.png' );
		echo '<meta name="msapplication-TileImage" content="' . $icon . '">';
	}

	/**
	 * Android Theme Color
	 */
	public function set_android_theme_color() {

		$theme_color = get_theme_mod( 'branding_customizer_android_theme', '#000000' );
		echo '<meta name="theme-color" content="' . $theme_color . '">';
	}

	/**
	 * IE Metro Icon Background Color
	 */
	public function set_ie_metro_icon_bgd_color() {

		$icon_bgd_color = get_theme_mod( 'branding_customizer_icon_ie_bgd_color', '#000000' );
		echo '<meta name="msapplication-TileColor" content="' . $icon_bgd_color . '">';
	}

	/**
	 * Add a Branding Icons section to the Theme Customizer
	 * and clean up a few other sections that are not needed
	 *
	 * @param $wp_customize
	 */
	public function theme_customizer( $wp_customize ) {

		// Clean up customizer a bit
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'static_front_page' );
		$wp_customize->remove_control( 'site_icon' );

		// Add branding section
		$wp_customize->add_section( 'branding_customizer', array(
			'title'    => apply_filters( 'branding_customizer_title', __( 'Branding', 'tribe' ) ),
			'priority' => apply_filters( 'branding_customizer_priority', 500 )
		) );

		// Icon: Login
		$wp_customize->add_setting( 'branding_customizer_icon_admin' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_admin',
			array(
				'label'       => __( 'Login Logo', 'tribe' ),
				'description' => __( 'Recommended minimum width: 700px. Recommended file type: .png.', 'tribe' ),
				'section'     => 'branding_customizer',
				'settings'    => 'branding_customizer_icon_admin',
			) ) );

		// Icon: Favicon
		$wp_customize->add_setting( 'branding_customizer_icon_favicon' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_favicon',
			array(
				'label'       => __( 'Favicon', 'tribe' ),
				'description' => __( 'Recommended dimensions: 64px X 64px. Recommended file type: .ico.', 'tribe' ),
				'section'     => 'branding_customizer',
				'settings'    => 'branding_customizer_icon_favicon',
				'extensions'  => array( 'ico' ),
			) ) );

		// Icon: Android
		$wp_customize->add_setting( 'branding_customizer_icon_android' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_android',
			array(
				'label'       => __( 'Android Icon', 'tribe' ),
				'description' => __( 'Recommended dimensions: 192px X 192px. Recommended file type: .png.', 'tribe' ),
				'section'     => 'branding_customizer',
				'settings'    => 'branding_customizer_icon_android'
			) ) );

		// Icon: iOS
		$wp_customize->add_setting( 'branding_customizer_icon_ios' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_ios',
			array(
				'label'       => __( 'iOS Icon', 'tribe' ),
				'description' => __( 'Recommended dimensions: 512px X 512px. Recommended file type: .png.', 'tribe' ),
				'section'     => 'branding_customizer',
				'settings'    => 'branding_customizer_icon_ios'
			) ) );

		// Icon: IE
		$wp_customize->add_setting( 'branding_customizer_icon_ie' );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branding_customizer_icon_ie', array(
			'label'       => __( 'IE Metro Icon', 'tribe' ),
			'description' => __( 'Recommended dimensions: 144px X 144px. Recommended file type: .png.', 'tribe' ),
			'section'     => 'branding_customizer',
			'settings'    => 'branding_customizer_icon_ie'
		) ) );

		// Android Theme
		$wp_customize->add_setting( 'branding_customizer_android_theme' );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'branding_customizer_android_theme',
			array(
				'label'       => __( 'Android Theme Color', 'tribe' ),
				'description' => __( 'Select the theme color for android os while website is active in chrome.', 'tribe' ),
				'section'     => 'branding_customizer',
				'settings'    => 'branding_customizer_android_theme'
			) ) );

		// Icon Bgd: IE
		$wp_customize->add_setting( 'branding_customizer_icon_ie_bgd_color' );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'branding_customizer_icon_ie_bgd_color', array(
				'label'       => __( 'IE Metro Icon Background Color', 'tribe' ),
				'description' => __( 'Select the background color to be used behind the IE Metro Icon.', 'tribe' ),
				'section'     => 'branding_customizer',
				'settings'    => 'branding_customizer_icon_ie_bgd_color'
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

		echo get_bloginfo( 'name' ) . ' running on <a href="http://wordpress.org/" rel="external">WordPress</a>';
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
