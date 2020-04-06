<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Footer;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Footer\Footer_Wrap as Footer_Context;
use Tribe\Project\Templates\Controllers\Footer\Footer_Default as Footer_Content;
use Twig\Environment;

class Footer_Wrap extends Abstract_Controller {
	/**
	 * @var Footer_Content
	 */
	private $content;

	public function __construct( Environment $twig, Component_Factory $factory, Footer_Content $content ) {
		parent::__construct( $twig, $factory );
		$this->content = $content;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Footer_Context::class, [
			Footer_Context::CONTENT => $this->content->render(),
		] )->render( $path );
	}

}
