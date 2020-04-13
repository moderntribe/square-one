<?php


namespace Tribe\Project\Templates\Controllers\Pages;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Pages\Error_404 as Error_404_Context;
use Tribe\Project\Templates\Controllers\Document\Document;

class Error_404 extends Abstract_Controller {
	/**
	 * @var Document
	 */
	private $document;

	public function __construct(
		Component_Factory $factory,
		Document $document
	) {
		parent::__construct( $factory );
		$this->document = $document;
	}

	public function render( string $path = '' ): string {
		return $this->document->render( $this->main( $this->factory->get( Error_404_Context::class, [
			Error_404_Context::TITLE   => $this->get_404_page_title(),
			Error_404_Context::CONTENT => $this->get_404_page_content(),
		] )->render( $path ) ) );
	}

	private function main( string $content ): string {
		return $this->factory->get( Main::class, [
			Main::HEADER  => '',
			Main::CONTENT => $content,
		] )->render();
	}

	protected function get_404_page_title(): string {
		return __( 'Whoops! We are having trouble locating your page!', 'tribe' );
	}

	protected function get_404_page_content(): string {
		return __( "If you're lost or have reached this page in error, our apologies. Please use the navigation menu or the links in the footer to find your way through the site. Please e-mail us if you have any questions.", 'tribe' );
	}

}
