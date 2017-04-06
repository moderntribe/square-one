<?php

namespace Tribe\Project;

use Pimple\Container;
use Tribe\Libs\Functions\Function_Includer;
use Tribe\Project\Service_Providers\Asset_Provider;
use Tribe\Project\Service_Providers\Cache_Provider;
use Tribe\Project\Service_Providers\Panel_Intializer_Provider;
use Tribe\Project\Service_Providers\Theme_Customizer_Provider;
use Tribe\Project\Service_Providers\Global_Service_Provider;
use Tribe\Project\Service_Providers\Theme_Provider;
use Tribe\Project\Service_Providers\Settings_Provider;
use Tribe\Project\Service_Providers\Util_Provider;

class Core {

	protected static $_instance;

	/** @var Container */
	protected $container = null;

	/**
	 * @param Container $container
	 */
	public function __construct( $container ) {
		$this->container                   = $container;
	}

	public function init() {
		$this->load_libraries();
		$this->load_functions();
		$this->load_service_providers();
	}

	private function load_libraries() {
		require_once( dirname( $this->container[ 'plugin_file' ] ) . '/vendor/johnbillion/extended-cpts/extended-cpts.php' );
		require_once( dirname( $this->container[ 'plugin_file' ] ) . '/vendor/johnbillion/extended-taxos/extended-taxos.php' );
	}

	private function load_functions() {
		Function_Includer::cache();
		Function_Includer::version();
	}

	private function load_service_providers() {
		$this->container->register( new Asset_Provider() );
		$this->container->register( new Cache_Provider() );
		$this->container->register( new Theme_Provider() );
		$this->container->register( new Theme_Customizer_Provider() );
		$this->container->register( new Panel_Intializer_Provider() );
		$this->container->register( new Global_Service_Provider() );
		$this->container->register( new Settings_Provider() );
		$this->container->register( new Util_Provider() );
	}

	public function container() {
		return $this->container;
	}

	/**
	 * @param null|\ArrayAccess $container
	 *
	 * @return Core
	 * @throws \Exception
	 */
	public static function instance( $container = null ) {
		if ( ! isset( self::$_instance ) ) {
			if ( empty( $container ) ) {
				throw new \Exception( 'You need to provide a Pimple container' );
			}

			$className       = __CLASS__;
			self::$_instance = new $className( $container );
		}

		return self::$_instance;
	}

}