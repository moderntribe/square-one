<?php


namespace Tribe\Project\ACF;


class Layout extends Field implements ACF_Aggregate {
	protected $key_prefix = 'layout';

	/** @var Field[] */
	protected $fields = [ ];

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