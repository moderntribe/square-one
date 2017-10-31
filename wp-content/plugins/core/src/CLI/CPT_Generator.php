<?php

namespace Tribe\Project\CLI;

use Pimple\Container;

class CPT_Generator extends \WP_CLI_Command {

	private $container;
	private $cpt_directory;
	private $slug;
	private $class_name;
	private $namespace;
	private $assoc_args;
	private $handle;
	private $config_handle;
	private $service_provider_handle;

	public function __construct( Container $container ) {
		$this->container = $container;
		parent::__construct();
	}

	/**
	 * Generates a CPT.
	 *
	 * ## EXAMPLES
	 *
	 *     wp s1 cpt
	 *
	 * ## OPTIONS
	 * <cpt-name>
	 * : The name of the CPT.
	 *
	 * [--config]
	 * : Whether or not to create a config file by default. Defaults to true, pass --no-config if you don't need one.
     *
	 * [--single=<single>]
	 * : Singular CPT.
	 *
	 * [--plural=<plural>]
	 * : Plural CPT.
	 *
	 * @when after_wp_load
	 */
	public function cpt( $args, $assoc_args ) {
		// Validate the slug.
		$this->slug = $this->sanitize_slug( $args );
		$this->class_name = ucfirst( $this->slug );
		$this->namespace = 'Tribe\Project\Post_Types\\' . $this->class_name;
		// Set up associate args with some defaults.
		$this->assoc_args = $this->parse_assoc_args( $assoc_args );

		// Create dir.
		$this->create_directory();

		// Create post object.
		$this->new_post_object_class();

		// Create service provider.
		$this->new_service_provider();
	}

	private function sanitize_slug( $args ) {
		list( $slug ) = $args;
		return sanitize_title( $slug );
	}

	private function create_directory() {
		$new_directory = trailingslashit( dirname( __DIR__, 1 ) ) . 'Post_Types/' . ucfirst( $this->slug );
		if ( file_exists( $new_directory ) ) {
			\WP_CLI::error( 'Sorry...this directory apparently already exists' );
		}
		if ( ! mkdir( $new_directory ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to create the dir' );
		}
		$this->cpt_directory = $new_directory;
	}

	private function parse_assoc_args( $assoc_args ) {
		$defaults = [
			'single' => ucfirst( $this->slug ),
			'plural' => ucfirst( $this->slug ) . 's',
			'config' => true,
		];
		return wp_parse_args( $assoc_args, $defaults );
	}

	private function new_post_object_class() {
		$this->new_post_object_class_file();
		if ( $this->assoc_args['config'] ) {
			$this->new_post_object_config_file();
		}

		$this->write_object_class_file();
		if ( $this->assoc_args['config'] ) {
			$this->write_object_config_file();
		}
	}

	private function new_service_provider() {
		$this->new_service_provider_file();
		$this->write_service_provider_file();
	}

	private function new_service_provider_file(){
		$new_service_provider = trailingslashit( dirname( __DIR__, 1 ) ) . 'Service_Providers/Post_Types/' . ucfirst( $this->slug ) . '_Service_Provider.php';
		if ( file_exists ( $new_service_provider ) ) {
			\WP_CLI::error( 'Sorry...this service provider apparently already exists.' );
		}
		if ( ! $handle = fopen( $new_service_provider, 'w' ) ) {
			\WP_CLI::error( 'Sorry...something unexpected happened when we tried to write the file' );
		}

		$this->service_provider_class = ucfirst( $this->slug );
		$this->service_provider_handle = $handle;
	}

	private function write_service_provider_file(){
		fwrite( $this->service_provider_handle, $this->get_service_provider_contents() );
	}

	private function get_service_provider_contents() {
		return <<< SERVICE_PROVIDER
<?php

namespace Tribe\Project\Service_Providers\Post_Types;

use Tribe\Project\Post_Types\\{$this->class_name};

class {$this->service_provider_class}_Service_Provider extends Post_Type_Service_Provider {
	protected \$post_type_class = {$this->class_name}\\{$this->class_name}::class;
	protected \$config_class = {$this->class_name}\Config::class;
}
SERVICE_PROVIDER;
	}

	private function new_post_object_class_file() {
		$new_class = trailingslashit( $this->cpt_directory ) . ucfirst( $this->slug ) . '.php';
		if ( file_exists( $new_class ) ) {
			\WP_CLI::error( 'Sorry...this class apparently already exists' );
		}
		if ( ! $handle = fopen( $new_class, 'w' ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to write to the new class' );
		}
		$this->handle = $handle;
	}

	private function new_post_object_config_file() {
		$new_config = trailingslashit( $this->cpt_directory ) . 'Config.php';
		if ( file_exists( $new_config ) ) {
			\WP_CLI::error( 'Sorry...this config file apparently already exists' );
		}
		if ( ! $config = fopen( $new_config, 'w' ) ) {
			\WP_CLI::error( 'Sorry...something went wrong when we tried to write to the new config file' );
		}
		$this->config_handle = $config;
	}

	private function write_object_class_file() {
		fwrite( $this->handle, $this->get_class_contents() );
	}

	private function get_class_contents() {
		return <<<PHP
<?php 

namespace {$this->namespace};

use Tribe\Libs\Post_Type\Post_Object;

class {$this->class_name} extends Post_Object {
	const NAME = '{$this->slug}';
}
PHP;
	}

	private function write_object_config_file() {
		fwrite( $this->config_handle, $this->get_config_contents() );
	}

	private function get_config_contents() {
		return <<<PHP
<?php

namespace {$this->namespace};

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( '{$this->class_name}', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor' ],
			'capability_type'  => 'post', // to use default WP caps
		];
	}

	public function get_labels() {
		return [
			'singular' => __( '{$this->assoc_args['single']}', 'tribe' ),
			'plural'   => __( '{$this->assoc_args['plural']}', 'tribe' ),
			'slug'     => __( '{$this->slug}', 'tribe' ),
		];
	}

}

PHP;
	}

}