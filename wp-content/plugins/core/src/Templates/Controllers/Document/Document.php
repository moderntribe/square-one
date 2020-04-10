<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Document;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Document\Document as Document_Context;
use Tribe\Project\Templates\Components\Footer\Site_Footer;
use Tribe\Project\Templates\Components\Header\Masthead;
use Tribe\Project\Templates\Controllers\Sidebar\Main_Sidebar;

class Document extends Abstract_Controller {
	/**
	 * @var Masthead
	 */
	private $masthead;

	/**
	 * @var Main
	 */
	private $main;

	/**
	 * @var Main_Sidebar
	 */
	private $sidebar;

	/**
	 * @var Site_Footer
	 */
	private $footer;

	public function __construct(
		Component_Factory $factory,
		Masthead $masthead,
		Main $main,
		Main_Sidebar $sidebar,
		Site_Footer $footer
	) {
		parent::__construct( $factory );
		$this->masthead = $masthead;
		$this->main     = $main;
		$this->sidebar  = $sidebar;
		$this->footer   = $footer;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Document_Context::class, [
			Document_Context::LANG       => $this->get_language_attributes(),
			Document_Context::BLOG_NAME  => get_bloginfo( 'name' ),
			Document_Context::PINGBACK   => get_bloginfo( 'pingback_url' ),
			Document_Context::TITLE      => $this->get_page_title(),
			Document_Context::BODY_CLASS => $this->get_body_class(),
			Document_Context::MASTHEAD   => $this->masthead->render(),
			Document_Context::MAIN       => $this->main->render(),
			Document_Context::SIDEBAR    => $this->sidebar->render(),
			Document_Context::FOOTER     => $this->footer->render(),
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
