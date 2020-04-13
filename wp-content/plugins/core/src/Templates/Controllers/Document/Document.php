<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Document;

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Document\Document as Document_Context;
use Tribe\Project\Templates\Controllers\Footer\Site_Footer;
use Tribe\Project\Templates\Controllers\Header\Masthead;
use Tribe\Project\Templates\Controllers\Sidebar\Main_Sidebar;

/**
 * Class Document
 *
 * Similar to, but not quite the same as, an Abstract_Controller.
 * A Document performs many of the same duties, but it expects
 * its central content to be passed in to the render() method.
 *
 * While this is still conceptually a "Controller", it does not
 * fit the expectations of the Controller_Interface and cannot
 * be used as one.
 */
class Document {
	/**
	 * @var Component_Factory
	 */
	protected $factory;
	/**
	 * @var Head
	 */
	private $head;
	/**
	 * @var Masthead
	 */
	private $masthead;
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
		Head $head,
		Masthead $masthead,
		Main_Sidebar $sidebar,
		Site_Footer $footer
	) {
		$this->factory  = $factory;
		$this->head     = $head;
		$this->masthead = $masthead;
		$this->sidebar  = $sidebar;
		$this->footer   = $footer;
	}

	/**
	 * Render the Document component, wrapping it around arbitrary content
	 *
	 * @param string $content The content of the page
	 * @param string $path    A template path to pass to the component's context
	 *
	 * @return string
	 */
	public function render( string $content = '', string $path = '' ): string {
		return $this->factory->get( Document_Context::class, [
			Document_Context::LANG       => $this->get_language_attributes(),
			Document_Context::BODY_CLASS => $this->get_body_class(),
			Document_Context::HEAD       => $this->head->render(),
			Document_Context::MASTHEAD   => $this->masthead->render(),
			Document_Context::SIDEBAR    => $this->sidebar->render(),
			Document_Context::FOOTER     => $this->footer->render(),
			Document_Context::MAIN       => $content,
		] )->render( $path );
	}


	protected function get_language_attributes() {
		ob_start();
		language_attributes();

		return ob_get_clean();
	}

	protected function get_body_class() {
		return implode( ' ', get_body_class() );
	}

}
