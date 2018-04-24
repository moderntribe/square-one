<?php

namespace Tribe\Project\CLI\Meta;

use Tribe\Project\CLI\Command;

class Importer extends Command {

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
	protected $constants  = [];

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
		$this->args       = $args;
		$this->assoc_args = $assoc_args;

		if ( empty( $this->get_dynamic_field_groups() ) ) {
			\WP_CLI::error( __( 'There are zero field groups available to import', 'tribe' ) );
		}

		if ( count( $args ) ) {
			// Setup and import the field groups.
			$this->setup_field_group();

			// Sanity check.
			\WP_CLI::confirm( sprintf( __( 'Are you sure you want to delete the database entry %s field group and convert it to php?', 'tribe' ), $this->title ), $assoc_args );

			// Write the meta files.
			$this->update_service_provider();
			$this->create_object_class();

			// Delete the field group.
			$this->delete_field_group();

			// Success!
			\WP_CLI::line( __( 'We did it!', 'tribe' ) );
		} else {
			foreach ( $this->get_dynamic_field_groups() as $field_group_id => $field_group_name ) {
				\WP_CLI::line( sprintf( __( 'You can import %s with `wp s1 import meta %s`', 'tribe' ), $field_group_name, $field_group_id ) );
			}
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
		$this->const_name = $this->file_system->constant_from_class( $this->slug );
		$this->pimple_key = strtolower( 'object_meta.' . $this->const_name );
	}

	protected function update_service_provider() {
		$object_meta_service_provider = trailingslashit( dirname( __DIR__, 2 ) ) . 'Service_Providers/Object_Meta_Provider.php';

		// Insert the Use.
		$this->file_system->insert_into_existing_file( $object_meta_service_provider, 'use ' . $this->namespace . ';' . PHP_EOL, 'use Tribe\Libs\Object_Meta\Meta_Repository;' );

		// Constant.
		$constant = "\tconst {$this->const_name} = '{$this->pimple_key}';" . PHP_EOL;
		$this->file_system->insert_into_existing_file( $object_meta_service_provider, $constant, 'const REPO    = \'object_meta.collection_repo\';' );

		// Keys.
		$key = "\t\tself::" . $this->const_name . ',' . PHP_EOL;
		$this->file_system->insert_into_existing_file( $object_meta_service_provider, $key, 'private $keys = [' );

		// public function register( Container $container ) {
		$container_partial_file = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/container_partial.php' );
		$container_partial      = sprintf( $container_partial_file, $this->class_name, $this->file_system->format_array_for_file( $this->build_object_array(), 4 ), $this->const_name );
		$this->file_system->insert_into_existing_file( $object_meta_service_provider, $container_partial, 'public function register( Container $container ) {' );

	}

	protected function build_object_array() {
		$locations = [];
		foreach ( $this->group['location'] as $location ) {
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
		$this->file_system->write_file( $object_class, $this->class_file_template() );
	}

	protected function class_file_template() {
		$class_file = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/object_meta.php' );

		return sprintf(
			$class_file,
			$this->class_name,
			$this->slug,
			$this->field_keys(),
			$this->title,
			$this->add_field_functions( $this->group['fields'] ),
			$this->field_functions( $this->group['fields'] ),
			$this->field_constants()
		);
	}

	protected function field_constants() {
		$constants = '';

		foreach ( $this->constants as $label => $name ) {
			$constants .= "\tconst " . $this->file_system->constant_from_class( $this->sanitize_slug( [ $label ] ) ) . ' = ' . '\'' . $this->slug . '_' . $name . '\';' . PHP_EOL;
		}

		return $constants;
	}

	protected function field_keys() {
		$keys = '';
		foreach ( $this->group['fields'] as $field ) {
			$keys .= "\t\t\tstatic::" . $this->file_system->constant_from_class( $this->sanitize_slug( [ $field['label'] ] ) ) . ',' . PHP_EOL;
		}

		return $keys;
	}

	protected function add_field_functions( $fields, $group = '$group', $indent = 8 ) {
		$functions = '';
		foreach ( $fields as $field ) {
			$functions .= str_repeat( ' ', $indent ) . $group . '->add_field( $this->get_field_' . $this->sanitize_slug( [ $field['label'] ] ) . '() );' . PHP_EOL;
		}

		return $functions;
	}

	protected function field_functions( $fields ) {
		$function_partial = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/field_function_partial.php' );

		$functions = '';
		foreach ( $fields as $field ) {

			$this->constants[ $field['label'] ] = $field['name'];

			$field = $this->prepare_field( $field );

			if ( 'repeater' !== $field['type'] ) {
				$functions .= sprintf(
					$function_partial,
					$this->sanitize_slug( [ $field['label'] ] ),
					$this->file_system->constant_from_class( $this->sanitize_slug( [ $field['label'] ] ) ),
					$this->file_system->format_array_for_file( $field, 16 )
				);
			} else {
				$functions .= $this->get_repeater( $field );
			}
		}

		return $functions;
	}

	protected function delete_field_group() {
		acf_delete_field_group( acf_get_field_group_id( $this->group ) );
	}

	private function prepare_field( $field ) {
		unset ( $field['key'], $field['wrapper'], $field['prepend'], $field['append'] );

		$field = array_filter( $field, function ( $element ) {
			return '' !== $element;
		} );

		$zero_fields = [
			'required',
			'conditional_logic',
		];

		foreach ( $zero_fields as $zero_field ) {
			if ( 0 === $field[ $zero_field ] ) {
				unset( $field[ $zero_field ] );
			}
		}

		$required_fields = [
			'name',
			'label',
		];

		foreach ( $required_fields as $required_field ) {
			if ( ! isset( $field[ $required_field ] ) ) {
				\WP_CLI::error( sprintf( __( '%s field must be set.', 'tribe' ), $required_field ) );
			}
		}

		return $field;
	}

	private function get_repeater( $field ) {
		$write_field = $field;
		unset ( $write_field['sub_fields'] );

		$group_partial = file_get_contents( trailingslashit( dirname( __DIR__, 3 ) ) . 'assets/templates/cli/object_meta/repeater_function_partial.php' );
		$group         = sprintf(
			$group_partial,
			$this->sanitize_slug( [ $field['label'] ] ),
			$this->file_system->constant_from_class( $this->sanitize_slug( [ $field['label'] ] ) ),
			$this->file_system->format_array_for_file( $write_field, 16 ),
			$this->add_field_functions( $field['sub_fields'], '$repeater' )
		);

		return $group . $this->field_functions( $field['sub_fields'] );
	}
}
