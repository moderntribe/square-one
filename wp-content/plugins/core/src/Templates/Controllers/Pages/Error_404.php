<?php


namespace Tribe\Project\Templates\Controllers\Pages;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Pages\Error_404 as Error_404_Context;
use Tribe\Project\Templates\Components\Pages\Page_Wrap;
use Tribe\Project\Templates\Controllers\Footer\Footer_Wrap;
use Tribe\Project\Templates\Controllers\Header\Header_Wrap;
use Tribe\Project\Templates\Template_Interface;

class Error_404 extends Abstract_Controller {
	/**
	 * @var Header_Wrap
	 */
	private $header;
	/**
	 * @var Footer_Wrap
	 */
	private $footer;

	public function __construct(
		Component_Factory $factory,
		Header_Wrap $header,
		Footer_Wrap $footer
	) {
		parent::__construct( $factory );
		$this->header = $header;
		$this->footer = $footer;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Page_Wrap::class, [
			Page_Wrap::HEADER  => $this->header->render(),
			Page_Wrap::FOOTER  => $this->footer->render(),
			Page_Wrap::CONTENT => $this->build_content()->render( $path ),
		] )->render();
	}

	protected function build_content(): Template_Interface {
		return $this->factory->get( Error_404_Context::class, [
			Error_404_Context::TITLE   => $this->get_404_page_title(),
			Error_404_Context::CONTENT => $this->get_404_page_content(),
		] );
	}

	protected function get_404_page_title(): string {
		return __( 'Whoops! We are having trouble locating your page!', 'tribe' );
	}

	protected function get_404_page_content(): string {
		return __( "If you're lost or have reached this page in error, our apologies. Please use the navigation menu or the links in the footer to find your way through the site. Please e-mail us if you have any questions.", 'tribe' );
	}

}
