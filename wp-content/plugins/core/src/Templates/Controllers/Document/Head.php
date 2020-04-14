<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Document;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Head as Head_Context;

class Head extends Abstract_Controller {

	public function __construct( Component_Factory $factory ) {
		parent::__construct( $factory );
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Head_Context::class, [
			Head_Context::LANG      => $this->get_language_attributes(),
			Head_Context::BLOG_NAME => get_bloginfo( 'name' ),
			Head_Context::PINGBACK  => get_bloginfo( 'pingback_url' ),
			Head_Context::TITLE     => $this->get_page_title(),
		] )->render( $path );
	}

	protected function get_language_attributes() {
		ob_start();
		language_attributes();

		return ob_get_clean();
	}

	protected function get_page_title() {
		if ( is_front_page() ) {
			return '';
		}

		// Blog
		if ( is_home() ) {
			return __( 'Blog', 'tribe' );
		}

		// Search
		if ( is_search() ) {
			return __( 'Search Results', 'tribe' );
		}

		// 404
		if ( is_404() ) {
			return __( 'Page Not Found (404)', 'tribe' );
		}

		// Singular
		if ( is_singular() ) {
			return get_the_title();
		}

		// Archives
		return get_the_archive_title();
	}

}
