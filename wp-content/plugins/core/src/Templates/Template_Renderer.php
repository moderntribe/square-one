<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Twig\Environment;

class Template_Renderer {
	/**
	 * @var Environment
	 */
	private $twig;

	public function __construct( Environment $twig ) {
		$this->twig = $twig;
	}

	/**
	 * @param string             $path
	 * @param Template_Interface $template
	 *
	 * @return string
	 */
	public function render( $path, Template_Interface $template ): string {
		return $this->twig->render( $path, $template->get_data() );
	}
}
