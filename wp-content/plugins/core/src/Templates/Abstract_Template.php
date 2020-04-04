<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Twig\Environment;

abstract class Abstract_Template implements Template_Interface {
	/**
	 * @var string Default path to be used to render this template
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
	 * @param Environment       $twig
	 * @param Component_Factory $factory
	 */
	public function __construct( Environment $twig, Component_Factory $factory ) {
		$this->twig    = $twig;
		$this->factory = $factory;
	}

	public function render( string $path = '' ): string {
		$path = $path ?: $this->get_path();
		if ( empty( $path ) ) {
			throw new \RuntimeException( 'Path not specified' );
		}
		try {
			return $this->twig->render( $path, $this->get_data() );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				throw $e;
			}

			return '';
		}
	}

	protected function get_path(): string {
		return $this->path;
	}

	public function get_data(): array {
		return [];
	}


}
