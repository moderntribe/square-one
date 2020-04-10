<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Document;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Main\Main as Main_Context;
use Tribe\Project\Templates\Controllers\Header\Subheader;
use Tribe\Project\Templates\Template_Interface;

class Main extends Abstract_Controller {
	/**
	 * @var Subheader
	 */
	private $subheader;

	public function __construct(
		Component_Factory $factory,
		Subheader $subheader
	) {
		parent::__construct( $factory );
		$this->subheader = $subheader;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Main_Context::class, [
			Main_Context::HEADER  => '',
			Main_Context::CONTENT => $this->build_content()->render( $path ),
		] )->render( $path );
	}

	protected function build_content(): Template_Interface {
		// TODO: Start the magic. The content of main is route-dependent.
		return $this->factory->get( '' );
	}
}
