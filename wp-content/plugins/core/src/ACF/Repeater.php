<?php


namespace Tribe\Project\ACF;


class Repeater extends Field implements ACF_Aggregate {
	protected $key_prefix = 'field';

	/** @var Field[] */
	protected $fields = [ ];

	public function __construct( $key ) {
		parent::__construct( $key );
		$this->attributes[ 'type' ] = 'repeater';
	}

	public function add_field( Field $field ) {
		$this->fields[] = $field;
	}

	public function get_attributes() {
		$attributes = parent::get_attributes();
		$attributes[ 'sub_fields' ] = [ ];
		foreach ( $this->fields as $f ) {
			$attributes[ 'sub_fields' ][] = $f->get_attributes();
		}
		return $attributes;
	}
}