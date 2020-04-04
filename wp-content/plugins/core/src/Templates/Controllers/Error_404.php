<?php


namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Pages\Error_404 as Error_404_Context;
use Tribe\Project\Templates\Controllers\Content\Header\Subheader;
use Twig\Environment;

class Error_404 extends Abstract_Template {
	/**
	 * @var Header
	 */
	private $header;
	/**
	 * @var Subheader
	 */
	private $subheader;
	/**
	 * @var Footer
	 */
	private $footer;

	public function __construct(
		Environment $twig,
		Component_Factory $factory,
		Header $header,
		Subheader $subheader,
		Footer $footer
	) {
		parent::__construct( $twig, $factory );
		$this->header    = $header;
		$this->subheader = $subheader;
		$this->footer    = $footer;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Error_404_Context::class, [
			Error_404_Context::HEADER    => $this->header->render(),
			Error_404_Context::SUBHEADER => $this->subheader->render(),
			Error_404_Context::FOOTER    => $this->footer->render(),
			Error_404_Context::TITLE     => $this->get_404_page_title(),
			Error_404_Context::CONTENT   => $this->get_404_page_content(),
		] )->render( $path );
	}

	protected function get_404_page_title() {
		return __( 'Whoops! We are having trouble locating your page!', 'tribe' );
	}

	protected function get_404_page_content() {
		return __( "If you're lost or have reached this page in error, our apologies. Please use the navigation menu or the links in the footer to find your way through the site. Please e-mail us if you have any questions.", 'tribe' );
	}

}
