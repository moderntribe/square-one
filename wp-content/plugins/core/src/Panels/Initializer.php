<?php


namespace Tribe\Project\Panels;


use ModularContent\Fields;
use ModularContent\PanelType;
use Tribe\Project\Panels\Types\Panel_Type_Config;
use Tribe\Project\Post_Types\Event\Event;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Taxonomies\Category\Category;

class Initializer {
	private $panel_types_to_initialize = [ ];

	/** @var \ModularContent\PanelViewFinder */
	public $ViewFinder = null;

	/** @var string Path to the root file of the plugin */
	private $plugin_file = '';

	public function __construct( $plugin_file = '' ) {
		$this->plugin_file = $plugin_file;
	}

	public function add_panel_config( $classname ) {
		$this->panel_types_to_initialize[] = $classname;
	}

	/**
	 * @return void
	 * @action plugins_loaded 9
	 */
	public function set_labels() {
		// these have to register early (before plugins_loaded:10)
		add_filter( 'modular_content_singular_label', function () {
			return __( 'Panel', 'tribe' );
		} );
		add_filter( 'modular_content_plural_label', function () {
			return __( 'Panels', 'tribe' );
		} );
	}

	/**
	 * @param \ModularContent\Plugin $plugin
	 *
	 * @return void
	 * @action panels_init
	 */
	public function initialize_panels( \ModularContent\Plugin $plugin ) {
		$plugin->do_not_filter_the_content();

		$this->set_supported_post_types();
		$this->set_view_directories();
		require_once( dirname( $this->plugin_file ) . '/functions/panels.php' );

		add_filter( 'modular_content_default_fields', [ $this, 'set_default_fields' ], 10, 2 );
		add_filter( 'modular_content_default_settings_fields', [ $this, 'set_default_settings' ], 10, 2 );
		add_filter( 'modular_content_posts_field_taxonomy_options', [ $this, 'set_available_query_taxonomies', ], 10, 1 );
		add_filter( 'modular_content_posts_field_p2p_options', [ $this, 'filter_p2p_options' ], 10, 1 );
		add_filter( 'panels_query_post_type_options', [ $this, 'add_post_type_options_for_queries' ], 10, 1 );
		add_filter( 'panels_input_query_filter', [ $this, 'set_order_for_queries' ], 10, 3 );
		add_filter( 'panels_input_query_filter', [ $this, 'rewrite_date_query_for_events' ], 10, 3 );

		$this->register_panels( $plugin->registry() );
	}

	/**
	 * Modify the scroll offset for the iframe to the themes needs (fixed nav)
	 *
	 * @param array $data Any js config data from the plugin
	 *
	 * @return array
	 *
	 * @filter panels_js_config
	 */
	public function modify_js_config( $data = [] ) {
		$data['iframe_scroll_offset'] = 120;
		return $data;
	}

	/**
	 * Set default post types that support panels. Additional
	 * post types can be added here, or when registering the
	 * post type.
	 *
	 * @return void
	 */
	protected function set_supported_post_types() {
		remove_post_type_support( 'post', 'modular-content' );
		add_post_type_support( 'page', 'modular-content' );
	}

	/**
	 * Set the `content/panels` directory in the theme
	 * as the directory containing panel templates.
	 * Templates from the parent theme can be overridden
	 * in the child theme.
	 *
	 * @return void
	 */
	protected function set_view_directories() {
		$this->ViewFinder = new \ModularContent\PanelViewFinder( trailingslashit( get_stylesheet_directory() ) . 'content/panels' );
		$this->ViewFinder->add_directory( trailingslashit( get_template_directory() ) . 'content/panels' );
	}

	/**
	 * @param \ModularContent\TypeRegistry $registry
	 *
	 * @return void
	 */
	private function register_panels( $registry ) {
		foreach ( $this->panel_types_to_initialize as $class ) {
			/** @var Panel_Type_Config $panel_type */
			$panel_type = new $class( $this );
			if ( $panel_type instanceof Panel_Type_Config ) {
				$panel_type->register( $registry, $this->ViewFinder );
			}
		}
	}


	/**
	 * @param $panel_type_id
	 *
	 * @return PanelType
	 */
	public function factory( $panel_type_id, $helper_text = '' ) {
		if ( !$helper_text ) {
			return new PanelType( $panel_type_id );
		}

		$helper_field = new Fields\HTML( [
			'name'        => 'panel-helper',
			'label'       => '',
			'description' => $helper_text,
		] );

		$default_fields_filter = function ( $fields, $panel_type ) use ( $helper_field ) {
			return array_merge( [ $helper_field ], $fields );
		};
		add_filter( 'modular_content_default_fields', $default_fields_filter, 20, 2 );
		$panel = new PanelType( $panel_type_id );
		remove_filter( 'modular_content_default_fields', $default_fields_filter, 20 );

		return $panel;
	}

	public function add_post_type_options_for_queries( $post_types ) {
		$post_types[ Event::NAME ] = get_post_type_object( Event::NAME );
		$post_types[ Page::NAME ] = get_post_type_object( Page::NAME );

		return $post_types;
	}

	public function set_available_query_taxonomies( $taxonomies ) {
		$taxonomies[] = Category::NAME;
		$taxonomies = array_unique( $taxonomies );
		sort( $taxonomies );

		return $taxonomies;
	}

	/**
	 * @param array $options
	 *
	 * @return array
	 */
	public function filter_p2p_options( $options ) {

		return $options;
	}

	public function set_default_fields( $fields, $panel_type ) {
		return $fields;
	}

	public function set_default_settings( $fields, $panel_type ) {
		return $fields;
	}

	/**
	 * A date-based query for the events post type should use
	 * appropriate args to search by event date rather than post date
	 *
	 * @param array      $query_args
	 * @param array      $filters
	 * @param int|string $context
	 *
	 * @return array
	 */
	public function rewrite_date_query_for_events( $query_args, $filters, $context ) {
		if ( !empty( $query_args[ 'date_query' ] ) ) {
			$is_event_query = false;
			if ( $query_args[ 'post_type' ] == 'tribe_events' ) {
				$is_event_query = true;
			} elseif ( is_array( $query_args[ 'post_type' ] ) && $query_args[ 'post_type' ] == [ 'tribe_events' ] ) {
				$is_event_query = true;
			}
			if ( $is_event_query ) {
				$dq = $query_args[ 'date_query' ];
				unset( $query_args[ 'date_query' ] );
				$query_args[ 'eventDisplay' ] = 'custom';
				if ( !empty( $dq[ 'after' ] ) ) {
					$query_args[ 'start_date' ] = $this->normalize_date_query_to_string( $dq[ 'after' ] );
				}
				if ( !empty( $dq[ 'before' ] ) ) {
					$query_args[ 'end_date' ] = $this->normalize_date_query_to_string( $dq[ 'before' ] );
				}
			}
		}
		return $query_args;
	}

	private function normalize_date_query_to_string( $input ) {
		if ( !is_array( $input ) ) {
			return (string) $input;
		}

		$date = isset( $input[ 'year' ] ) ? sprintf( '%04d', $input[ 'year' ] ) : date( 'Y' );
		$date .= '-';
		$date .= isset( $input[ 'month' ] ) ? sprintf( '%02d', $input[ 'month' ] ) : date( 'm' );
		$date .= '-';
		$date .= isset( $input[ 'day' ] ) ? sprintf( '%02d', $input[ 'day' ] ) : date( 'd' );
		$date .= " 23:59:59";
		return $date;

	}

	/**
	 * Some queries should not use the default sort order
	 *
	 * @param array      $query_args
	 * @param array      $filters
	 * @param int|string $context
	 *
	 * @return array
	 */
	public function set_order_for_queries( $query_args, $filters, $context ) {
		$post_type = !empty( $query_args[ 'post_type' ] ) ? $query_args[ 'post_type' ] : 'any';
		if ( is_array( $post_type ) && count( $post_type ) == 1 ) {
			$post_type = reset( $post_type );
		}
		if ( is_array( $post_type ) ) {
			return $query_args; // don't filter if it's for multiple post types
		}

		if ( 'tribe_events' == $post_type ) {
			$query_args[ 'orderby' ] = 'event_date';
			$query_args[ 'order' ] = 'ASC';
			$query_args[ 'eventDisplay' ] = 'list';
		} elseif ( in_array( $post_type, $this->post_types_to_sort_alphabetically() ) ) {
			$query_args[ 'orderby' ] = 'title';
			$query_args[ 'order' ] = 'ASC';
		}

		// else: defaults to descending order by date

		return $query_args;
	}

	protected function post_types_to_sort_alphabetically() {
		return [
			Page::NAME,
		];
	}

	public function thumbnail_url( $filename ) {
		return plugins_url( 'assets/admin/panels/thumbnails/' . $filename, $this->plugin_file );
	}

	public function layout_icon_url( $filename ) {
		return plugins_url( 'assets/admin/panels/icons/standard/' . $filename, $this->plugin_file );
	}

	public function swatch_icon_url( $filename ) {
		return plugins_url( 'assets/admin/panels/icons/swatches/' . $filename, $this->plugin_file );
	}

	/**
	 * @param string $hook_suffix
	 *
	 * @return void
	 * @action admin_enqueue_scripts
	 */
	public function enqueue_admin_css( $hook_suffix ) {
		if ( $hook_suffix === 'post.php' || $hook_suffix === 'post-new.php' ) {
			wp_enqueue_style( 'core-panels-admin', plugins_url( 'assets/admin/panels/panels.css', $this->plugin_file ), [], tribe_get_version() );
		}
	}
}
