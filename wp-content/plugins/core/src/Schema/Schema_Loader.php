<?php


namespace Tribe\Project\Schema;


class Schema_Loader {
	private $schema_classes;

	/**
	 * @param array $schema_classes A list of classes extending Schools
	 */
	public function __construct( array $schema_classes = [] ) {
		$this->schema_classes = $schema_classes;
	}

	public function add( $schema_class ) {
		$this->schema_classes[] = $schema_class;
	}

	public function hook() {
		if ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) {
			add_action( 'admin_init', [ $this, 'load_schema_updates' ], 10, 0 );
		}
	}

	/**
	 * Load any expected updates for the given schemata
	 *
	 * @return void
	 */
	public function load_schema_updates() {
		foreach ( $this->schema_classes as $classname ) {
			/** @var Schema $schema */
			$schema = new $classname;
			if ( $schema->update_required() ) {
				$schema->do_updates();
			}
		}
	}
}