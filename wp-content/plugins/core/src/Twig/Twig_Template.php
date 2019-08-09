<?php


namespace Tribe\Project\Twig;

abstract class Twig_Template implements Template_Interface {

	protected $template;
	protected $twig;

	/**
	 * @param string            $template
	 * @param \Twig_Environment $twig
	 */
	public function __construct( $template, \Twig_Environment $twig = null ) {
		$this->template = $template;
		if ( ! isset( $twig ) ) {
			$twig = apply_filters( 'tribe/project/twig', null );
			if ( empty( $twig ) ) {
				throw new \InvalidArgumentException( 'A Twig_Environment must be supplied, either directory or via the "tribe/project/twig" filter' );
			}
		}
		$this->twig = $twig;
	}

	public function render( $template = '' ): string  {
		$template = $template ?: $this->template;
		return $this->twig->render( $template, $this->get_data() );
	}
}
