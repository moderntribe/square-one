<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content\Header\Default_Header as Header_Content;
use Twig\Environment;

class Header extends Abstract_Template {
	protected $path = 'header.twig';

	/**
	 * @var Header_Content
	 */
	private $content;

	public function __construct( Environment $twig, Component_Factory $factory, Header_Content $content ) {
		parent::__construct( $twig, $factory );
		$this->content = $content;
	}

	public function get_data(): array {
		return [
			'content'             => $this->content->render(),
			'language_attributes' => $this->get_language_attributes(),
			'name'                => get_bloginfo( 'name' ),
			'pingback_url'        => get_bloginfo( 'pingback_url' ),
			'page_title'          => $this->get_page_title(),
			'body_class'          => $this->get_body_class(),
		];
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
