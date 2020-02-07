<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Twig\Environment;

abstract class Abstract_Template implements Template_Interface {
	/**
	 * @var string
	 */
	protected $path = '';
	/**
	 * @var Environment
	 */
	protected $twig;
	/**
	 * @var Component_Factory
	 */
	protected $factory;

	/**
	 * @param string            $path
	 * @param Environment       $twig
	 * @param Component_Factory $factory
	 */
	public function __construct( string $path, Environment $twig, Component_Factory $factory ) {
		$this->path    = $path;
		$this->twig    = $twig;
		$this->factory = $factory;
	}

	public function render(): string {
		if ( empty( $this->path ) ) {
			throw new \RuntimeException( 'Path not specified' );
		}
		try {
			return $this->twig->render( $this->path, $this->get_data() );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				throw $e;
			}

			return '';
		}
	}
}
