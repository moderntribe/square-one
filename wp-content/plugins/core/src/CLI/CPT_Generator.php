<?php

namespace Tribe\Project\CLI;

class CPT_Generator extends Square_One_Command {
	use File_System;

	private $cpt_directory;
	private $slug;
	private $class_name;
	private $namespace;
	private $assoc_args;

	public function description() {
		return 'Generates a CPT.';
	}

	public function callback() {
		return [ $this, 'cpt' ];
	}

	public function command() {
		return 'cpt';
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'cpt',
				'optional'    => false,
				'description' => 'The name of the CPT.',
			],
			[
				'type'        => 'flag',
				'name'        => 'config',
				'optional'    => true,
				'description' => 'Whether or not to create a config file by default. Defaults to true, pass --no-config if you don\'t need one.',
				'default'     => true,
			],
			[
				'type'        => 'generic',
				'name'        => 'single',
				'optional'    => true,
				'description' => 'Singular CPT.',
			],
			[
				'type'        => 'generic',
				'name'        => 'plural',
				'optional'    => true,
				'description' => 'Plural CPT.',
			],
		];
	}

	public function cpt( $args, $assoc_args ) {
		// Validate the slug.
		$this->slug       = $this->sanitize_slug( $args );
		$this->class_name = ucfirst( $this->slug );
		$this->namespace  = 'Tribe\Project\Post_Types\\' . $this->class_name;
		// Set up associate args with some defaults.
		$this->assoc_args = $this->parse_assoc_args( $assoc_args );

		// Create dir.
		$this->create_cpt_directory();

		// Create post object.
		$this->new_post_object_class();

		// Create service provider.
		$this->new_service_provider();

		\WP_CLI::success( 'Way to go! ' . \WP_CLI::colorize( "%W{$this->slug}%n" ) . ' post type has been created' );
	}

	private function sanitize_slug( $args ) {
		list( $slug ) = $args;

		return sanitize_title( $slug );
	}

	private function create_cpt_directory() {
		$new_directory = trailingslashit( dirname( __DIR__, 1 ) ) . 'Post_Types/' . ucfirst( $this->slug );
		$this->create_directory( $new_directory );
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
	}

	private function new_service_provider() {
		$this->new_service_provider_file();
	}

	private function new_service_provider_file() {
		$new_service_provider         = trailingslashit( dirname( __DIR__, 1 ) ) . 'Service_Providers/Post_Types/' . ucfirst( $this->slug ) . '_Service_Provider.php';
		$this->service_provider_class = ucfirst( $this->slug );
		$this->write_file( $new_service_provider, $this->get_service_provider_contents() );
	}

	private function new_post_object_class_file() {
		$class_file = trailingslashit( $this->cpt_directory ) . ucfirst( $this->slug ) . '.php';
		$this->write_file( $class_file, $this->get_class_contents() );
	}

	private function new_post_object_config_file() {
		$config = trailingslashit( $this->cpt_directory ) . 'Config.php';
		$this->write_file( $config, $this->get_config_contents() );
	}

	///------------templates---------------///

	private function get_class_contents() {
		return "<?php 
		
		namespace {$this->namespace};
		
		use Tribe\Libs\Post_Type\Post_Object;
		
		class {$this->class_name} extends Post_Object {
			const NAME = '{$this->slug}';
		}";
	}

	private function get_config_contents() {
		return "<?php

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
		
		}";
	}

	private function get_service_provider_contents() {
		return "<?php
		
		namespace Tribe\Project\Service_Providers\Post_Types;
		
		use Tribe\Project\Post_Types\\{$this->class_name};
		
		class {$this->service_provider_class}_Service_Provider extends Post_Type_Service_Provider {
			protected \$post_type_class = {$this->class_name}\\{$this->class_name}::class;
			protected \$config_class = {$this->class_name}\Config::class;
		}";
	}

}