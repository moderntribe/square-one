<?php


namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content\Header\Subheader;
use Twig\Environment;

class Error_404 extends Abstract_Template {
	protected $path = '404.twig';

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


	public function get_data(): array {
		$data = [
			'header'                    => $this->header->render(),
			'subheader'                 => $this->subheader->render(),
			'footer'                    => $this->footer->render(),
			'error_404_browser_title'   => $this->get_404_page_title(),
			'error_404_browser_content' => $this->get_404_page_content(),
		];

		return $data;
	}

	protected function get_404_page_title() {
		return __( 'Whoops! We are having trouble locating your page!', 'tribe' );
	}

	protected function get_404_page_content() {
		return __( "If you're lost or have reached this page in error, our apologies. Please use the navigation menu or the links in the footer to find your way through the site. Please e-mail us if you have any questions.", 'tribe' );
	}

}
