<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Builder\Factories\Builder_Factory;
use Tribe\Project\Controllers\Blocks\Block_Controller;

abstract class Block_Type_Config {
	/** @var Builder_Factory */
	protected $factory;

	public function __construct( Builder_Factory $factory ) {
		$this->factory = $factory;
	}

	/**
	 * Check the directory for the Block to get the corresponding Controller.php file, if it exists.
	 *
	 * @return bool|string
	 * @throws \ReflectionException
	 */
	public function get_controller_for_block() {
		$refl = new \ReflectionClass( static::class );
		$dir  = dirname( $refl->getFileName() );

		$files       = scandir( $dir );
		$controllers = array_filter( $files, function ( $file ) {
			$basename = basename( $file, '.php' );

			return $basename === 'Controller';
		} );

		if ( empty( $controllers ) ) {
			return false;
		}

		return sprintf( '%s\\Controller', $refl->getNamespaceName() );
	}

	abstract public function build(): Block_Type_Interface;
}
