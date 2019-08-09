<?php

namespace Tribe\Project\Theme;

class Logo {
	private $args = [];

	private function __construct( $args ) {
		$this->args         = $args;
		$this->args['echo'] = false;
	}

	public function get_logo() {
		return sprintf(
			'<%1$s class="logo" data-js="logo"><a href="%2$s" rel="home">%3$s</a></%1$s>',
			( is_front_page() ) ? 'h1' : 'div',
			esc_url( home_url() ),
			get_bloginfo( 'blogname' )
		);
	}

	/**
	 * A caching wrapper around logo
	 *
	 * @param array $args
	 * @return string|void
	 */
	public static function logo( $args ) {
		$logo = new self( $args );
		$html = $logo->get_logo();
		$echo = isset( $args['echo'] ) ? $args['echo'] : true;
		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

}
