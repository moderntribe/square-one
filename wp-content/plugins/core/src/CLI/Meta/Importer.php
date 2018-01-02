<?php

namespace Tribe\Project\CLI\Meta;

use Tribe\Project\CLI\Command;
use Tribe\Project\CLI\File_System;

class Importer extends Command {
	use File_System;

	protected $args       = [];
	protected $assoc_args = [];
	protected $group      = [];
	protected $key        = '';
	protected $title      = '';
	protected $slug       = '';
	protected $class_name = '';
	protected $namespace  = '';
	protected $const_name = '';
	protected $pimple_key = '';

	public function description() {
		return __( 'Generates object meta.', 'tribe' );
	}

	public function command() {
		return 'import meta';
	}

	public function arguments() {
		return [
			[
				'type'     => 'positional',
				'name'     => 'field_group',
				'optional' => true,
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		$this->args = $args;
		$this->assoc_args = $assoc_args;

		if ( empty( $this->get_dynamic_field_groups() ) ) {
			\WP_CLI::error( __( 'There are zero field groups available to import', 'tribe' ) );
		}

		if ( count( $args ) ) {
			// Setup and import the field groups.
			$this->setup_field_group();

			// Sanity check.
			\WP_CLI::confirm( 'Are you sure you want to delete the databse entry ' . $this->title . ' field group and convert it to php?', $assoc_args );
			
			// Write the meta files.
			$this->update_service_provider();
			$this->create_object_class();

			// Delete the field group.
			$this->delete_field_group();

			// Success!
			\WP_CLI::line( 'We did it!');
		}

		foreach( $this->get_dynamic_field_groups() as $field_group_id => $field_group_name ) {
			\WP_CLI::line( 'You can import ' . $field_group_name . ' with `wp s1 import meta ' . $field_group_id . '`');
		}
	}

	protected function get_dynamic_field_groups() {
		$field_groups = [];
		foreach ( acf_get_field_groups() as $field_group ) {

			// If it's already a php field group we won't do anything with it.
			if ( ! isset( $field_group['local'] ) || 'php' !== $field_group['local'] ) {
				$field_groups[ $field_group['key'] ] = $field_group['title'];
			}
		}

		return $field_groups;
	}

	protected function setup_field_group() {
		$group            = acf_get_field_group( $this->args[0] );
		$group['fields']  = acf_get_fields( $group );
		$this->group      = acf_prepare_field_group_for_export( $group );
		$this->key        = $this->group['key'];
		$this->title      = $this->group['title'];
		$this->slug       = $this->sanitize_slug( [ $this->title ] );
		$this->class_name = $this->ucwords( $this->slug );
		$this->namespace  = 'Tribe\Project\Object_Meta\\' . $this->class_name;
		$this->const_name = $this->constant_from_class( $this->slug );
		$this->pimple_key = strtolower( 'object_meta.' . $this->const_name );
	}

	protected function update_service_provider() {
		$object_meta_service_provider = trailingslashit( dirname( __DIR__, 2 ) ) . 'Service_Providers/Object_Meta_Provider.php';

		// Insert the Use.
		$this->insert_into_existing_file( $object_meta_service_provider, 'use ' . $this->namespace . ';' . PHP_EOL, 'use Tribe\Libs\Object_Meta\Meta_Repository;' );

		// Constant.
		$constant = "\tconst {$this->const_name} = '{$this->pimple_key}';" . PHP_EOL;
		$this->insert_into_existing_file( $object_meta_service_provider, $constant, 'const REPO    = \'object_meta.collection_repo\';' );

		// Keys.
		$key = "\t\tself::" . $this->const_name . ',' . PHP_EOL;
		$this->insert_into_existing_file( $object_meta_service_provider, $key, 'private $keys = [' );

		// public function register( Container $container ) {
		$container_partial_file = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/container_partial.php' );
		$container_partial = sprintf( $container_partial_file, $this->class_name, $this->format_array_for_file( $this->build_object_array(), 4 ), $this->const_name );
		$this->insert_into_existing_file( $object_meta_service_provider, $container_partial, 'public function register( Container $container ) {' );

	}

	protected function build_object_array() {
		$locations = [];
		foreach( $this->group['location'] as $location ) {
			if ( count( $location ) > 1 ) {
				\WP_CLI::error( 'Sorry, this importer does not yet support conditional location logic' );
			}
			switch ( $location[0]['param'] ) {
				case 'post_type':
					$locations['post_types'][] = $location[0]['value'];
					break;
				case 'post_category':
					$locations['taxonomies'][] = substr( $location[0]['value'], strpos( $location[0]['value'], ':' ) + 1 );
					break;
				case 'options_page':
					$locations['settings_pages'][] = $location[0]['value'];
					break;
				case 'user_form':
					$locations['users'] = true;
					break;
			}
		}

		return $locations;
	}

	protected function create_object_class() {
		$object_class = trailingslashit( dirname( __DIR__, 2 ) ) . 'Object_Meta/' . $this->class_name . '.php';
		$this->write_file( $object_class, $this->class_file_template() );
	}

	protected function class_file_template() {
		$class_file = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/object_meta.php' );

		return sprintf(
			$class_file,
			$this->class_name,
			$this->slug,
			$this->field_constants(),
			$this->field_keys(),
			$this->title,
			$this->add_field_functions(),
			$this->field_functions()
		);
	}

	protected function field_constants() {
		$constants = '';

		foreach ( $this->group['fields'] as $field ) {
			$constants .= "\tconst " . $this->constant_from_class( $this->sanitize_slug( [$field['label']] ) ) . ' = ' . '\'' . $this->slug . '_' . $field['name'] . '\';' . PHP_EOL;
		}
		return $constants;
	}

	protected function field_keys() {
		$keys = '';
		foreach ( $this->group['fields'] as $field ) {
			$keys .= "\t\t\tstatic::" . $this->constant_from_class( $this->sanitize_slug( [ $field['label'] ] ) ) . ',' . PHP_EOL;
		}
		return $keys;
	}

	protected function add_field_functions() {
		$functions = '';
		foreach ( $this->group['fields'] as $field ) {
			$functions .= '$group->add_field( $this->get_field_' . $this->sanitize_slug( [$field['label']] ) . '() );';
		}

		return $functions;
	}

	protected function field_functions() {
		$function_partial = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/field_function_partial.php' );

		$functions = '';
		foreach ( $this->group['fields'] as $field ) {
			unset( $field['key'] );
			$functions .= sprintf(
				$function_partial,
				$this->sanitize_slug( [ $field['label'] ] ),
				print_r( $field, 1 )
			);
		}

		return $functions;
	}

	protected function delete_field_group() {
		acf_delete_field_group( acf_get_field_group_id( $this->group ) );
	}
}
