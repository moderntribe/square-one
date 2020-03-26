<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers\Content\Footer\Default_Footer as Footer_Content;
use Twig\Environment;

class Footer extends Abstract_Template {
	protected $path = 'footer.twig';

	/**
	 * @var Footer_Content
	 */
	private $content;

	public function __construct( Environment $twig, Component_Factory $factory, Footer_Content $content ) {
		parent::__construct( $twig, $factory );
		$this->content = $content;
	}

	public function get_data(): array {
		return [
			'content' => $this->content->render(),
		];
	}

}
