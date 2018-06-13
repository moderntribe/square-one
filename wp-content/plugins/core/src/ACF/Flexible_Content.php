<?php


namespace Tribe\Project\ACF;

class Flexible_Content extends Field {
	/** @var Layout[] */
	protected $layouts = [ ];

	public function __construct( $key ) {
		parent::__construct( $key );
		$this->attributes[ 'type' ] = 'flexible_content';
	}

	public function add_layout( Layout $layout ) {
		$this->layouts[] = $layout;
	}

	public function get_attributes() {
		$attributes = parent::get_attributes();
		$attributes[ 'layouts' ] = [ ];
		foreach ( $this->layouts as $layout ) {
			$attributes[ 'layouts' ][] = $layout->get_attributes();
		}
		return $attributes;
	}
}