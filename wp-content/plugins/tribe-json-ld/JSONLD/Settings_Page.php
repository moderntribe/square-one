<?php


namespace JSONLD;

class Settings_Page {
	const SLUG                  = 'structured-data';
	const ORG_NAME              = 'tribe_jsonld_organization_name';
	const ORG_AUTHOR            = 'tribe_jsonld_organization_author';
	const ORG_COPYRIGHT_HOLDER  = 'tribe_jsonld_organization_copyright_holder';
	const ORG_CREATOR           = 'tribe_jsonld_organization_creator';
	const ORG_LOGO              = 'tribe_jsonld_organization_logo';
	const ENABLE_SEARCH_ACTION  = 'tribe_jsonld_search_action_enabled';
	const ARTICLE_DEFAULT_IMAGE = 'tribe_jsonld_article_logo';
	const ARTICLE_DEFAULT_TYPE  = 'tribe_jsonld_article_default_type';

	private static $instance = null;

	private $fields = [ ];

	public function __construct() {
		$this->setup_fields();
	}

	private function setup_fields() {
		$this->fields = [
			self::ORG_NAME              => [
				'label' => __( 'Organization Name', 'tribe' ),
				'type'  => 'text',
			],
			self::ORG_AUTHOR            => [
				'label' => __( 'Organization Author', 'tribe' ),
				'type'  => 'text',
			],
			self::ORG_COPYRIGHT_HOLDER  => [
				'label' => __( 'Organization Copyright Holder', 'tribe' ),
				'type'  => 'text',
			],
			self::ORG_CREATOR           => [
				'label' => __( 'Organization Creator', 'tribe' ),
				'type'  => 'text',
			],
			self::ORG_LOGO              => [
				'label'       => __( 'Organization Logo', 'tribe' ),
				'description' => __( 'Upload a minimum 500px size logo for use in Google results that has some padding (clear/white) top and right', 'tribe' ),
				'type'        => 'image',
			],
			self::ENABLE_SEARCH_ACTION  => [
				'label'   => __( 'Enable SearchAction', 'tribe' ),
				'type'    => 'select',
				'options' => [
					'yes' => __( 'Enabled', 'tribe' ),
					'no'  => __( 'Disabled', 'tribe' ),
				],
			  'default' => 'yes',
			],
			self::ARTICLE_DEFAULT_IMAGE => [
				'label'       => __( 'Article Default Image', 'tribe' ),
				'description' => __( 'A default image to be used by articles if no featured image is in place. Leave empty to not send one.', 'tribe' ),
				'type'        => 'image',
			],
			self::ARTICLE_DEFAULT_TYPE  => [
				'label'       => __( 'Default Single Type', 'tribe' ),
				'description' => __( 'The default type used for singles', 'tribe' ),
				'default'     => 'WebPage',
				'type'        => 'text',
			],
		];
	}

	private function hook() {
		add_action( 'admin_menu', [ $this, 'register_admin_page' ], 10, 0 );
	}


	public function register_admin_page() {
		add_options_page(
			__( 'Structured Data', 'tribe' ),
			__( 'Structured Data', 'tribe' ),
			'manage_options',
			self::SLUG,
			[ $this, 'display_admin_page' ]
		);

		add_settings_section(
			'default',
			'',
			'__return_false',
			self::SLUG
		);

		$this->register_fields();
	}

	private function register_fields() {
		foreach ( $this->fields as $field_name => $options ) {
			$options = wp_parse_args( $options, [
				'label'       => $field_name,
				'description' => '',
				'type'        => 'text',
				'default'     => '',
				'options'     => [ ],
				'section'     => 'default',
			] );
			$options[ 'name' ] = $field_name;
			add_settings_field(
				$field_name,
				$options[ 'label' ],
				$this->get_callback( $options[ 'type' ] ),
				self::SLUG,
				$options[ 'section' ],
				$options
			);
			register_setting(
				self::SLUG,
				$field_name
			);
		}
	}

	private function get_callback( $type ) {
		switch ( $type ) {
			case 'image':
				return [ $this, 'render_image_field' ];
			case 'select':
				return [ $this, 'render_select_field' ];
			case 'text':
			default:
				return [ $this, 'render_text_field' ];
		}
	}

	public function render_image_field( $args ) {
		$value = get_option( $args[ 'name' ], $args[ 'default' ] );
		$field = new \AttachmentHelper\Field([
			'label' => $args[ 'label' ],
			'value' => $value,
			'size'  => 'thumbnail',
			'name'  => $args[ 'name' ],
		]);

		if ( !empty( $args[ 'description' ] ) ) {
			printf( '<p class="description">%s</p>', $args[ 'description' ] );
		}
		$field->render();
	}

	public function render_select_field( $args ) {
		$value = get_option( $args[ 'name' ], $args[ 'default' ] );
		printf( '<select name="%s" class="widefat">', esc_attr( $args[ 'name' ] ) );
		foreach ( $args[ 'options' ] as $option_key => $label ) {
			printf( '<option value="%s" %s>%s</option>', esc_attr( $option_key ), selected( $option_key, $value, false ), $label );
		}
		if ( !empty( $args[ 'description' ] ) ) {
			printf( '<p class="description">%s</p>', $args[ 'description' ] );
		}
	}

	public function render_text_field( $args ) {
		$value = get_option( $args[ 'name' ], $args[ 'default' ] );
		printf( '<input name="%s" value="%s" class="widefat">', esc_attr( $args[ 'name' ] ), esc_attr( $value ) );
		if ( !empty( $args[ 'description' ] ) ) {
			printf( '<p class="description">%s</p>', $args[ 'description' ] );
		}
	}

	public function display_admin_page() {
		$title = __( 'Structured Data', 'tribe' );
		$action = admin_url( 'options.php' );
		ob_start();
		printf( '<form action="%s" method="post">', $action );
		settings_fields( self::SLUG );
		do_settings_sections( self::SLUG );
		submit_button();
		echo "</form>";
		$content = ob_get_clean();

		printf( '<div class="wrap"><h2>%s</h2>%s</div>', $title, $content );
	}

	public function get_option( $name ) {
		$default = '';
		if ( isset( $this->fields[ $name ] ) && isset( $this->fields[ 'name' ][ 'default' ] ) ) {
			$default = $this->fields[ 'name' ][ 'default' ];
		}
		return get_option( $name, $default );
	}

	public static function init() {
		self::instance()->hook();
	}

	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}