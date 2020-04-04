<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Header\Header_Wrap;
use Tribe\Project\Templates\Controllers\Content\Header\Default_Header as Header_Content;
use Twig\Environment;

class Header extends Abstract_Template {
	/**
	 * @var Header_Content
	 */
	private $content;

	public function __construct( Environment $twig, Component_Factory $factory, Header_Content $content ) {
		parent::__construct( $twig, $factory );
		$this->content = $content;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Header_Wrap::class, [
			Header_Wrap::CONTENT    => $this->content->render(),
			Header_Wrap::LANG       => $this->get_language_attributes(),
			Header_Wrap::BLOG_NAME  => get_bloginfo( 'name' ),
			Header_Wrap::PINGBACK   => get_bloginfo( 'pingback_url' ),
			Header_Wrap::TITLE      => $this->get_page_title(),
			Header_Wrap::BODY_CLASS => $this->get_body_class(),
		] )->render( $path );
	}

	protected function get_language_attributes() {
		ob_start();
		language_attributes();

		return ob_get_clean();
	}

	protected function get_page_title() {
		return get_page_title();
	}

	protected function get_body_class() {
		return implode( ' ', get_body_class() );
	}

}
