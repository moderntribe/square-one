<?php

namespace Tribe\Project\Twig;

use Twig\Environment;


abstract class Twig_Template implements Template_Interface {

	protected $template;
	protected $twig;

	/**
	 * @param string $template
	 * @param Environment $twig
	 */
	public function __construct( $template, Environment $twig = null ) {
		$this->template = $template;
		if ( ! isset( $twig ) ) {
			$twig = apply_filters( 'tribe/project/twig', null );
			if ( empty( $twig ) ) {
				throw new \InvalidArgumentException( 'A Twig\Environment must be supplied, either directory or via the "tribe/project/twig" filter' );
			}
		}
		$this->twig = $twig;
	}

	public function render( $template = '' ): string {
		$template = $template ?: $this->template;

		return $this->twig->render( $template, $this->get_data() );
	}
}