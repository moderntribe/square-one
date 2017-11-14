<?php

namespace Tribe\Project\CLI\Meta;

use Tribe\Project\CLI\Command;
use Tribe\Project\CLI\File_System;

class Importer extends Command {
	use File_System;

	protected $args = [];
	protected $assoc_args = [];
	protected $group = [];

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

		if ( count( $args ) ) {
			// Setup and import the field groups.
			$this->setup_field_group();

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
			if ( ! isset( $field_group['local'] ) || 'php' !== $field_group['local'] ) {
				$field_groups[ $field_group['key'] ] = $field_group['title'];
			}
		}

		return $field_groups;
	}

	protected function setup_field_group() {
		$group           = acf_get_field_group( $this->args[0] );
		$group['fields'] = acf_get_fields( $group );
		$this->group     = acf_prepare_field_group_for_export( $group );
	}

	protected function update_service_provider() {

	}
}
