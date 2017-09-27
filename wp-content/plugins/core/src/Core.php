<?php

namespace Tribe\Project;

use Pimple\Container;
use Tribe\Libs\Functions\Function_Includer;
use Tribe\Project\Service_Providers\Asset_Provider;
use Tribe\Project\Service_Providers\Cache_Provider;
use Tribe\Project\Service_Providers\CLI_Provider;
use Tribe\Project\Service_Providers\Object_Meta_Provider;
use Tribe\Project\Service_Providers\Nav_Menu_Provider;
use Tribe\Project\Service_Providers\Panels_Provider;
use Tribe\Project\Service_Providers\Post_Types\Event_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Organizer_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Page_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Post_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Sample_Post_Type_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Venue_Service_Provider;
use Tribe\Project\Service_Providers\Shortcode_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Category_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Example_Taxonomy_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Post_Tag_Service_Provider;
use Tribe\Project\Service_Providers\Theme_Customizer_Provider;
use Tribe\Project\Service_Providers\P2P_Provider;
use Tribe\Project\Service_Providers\Theme_Provider;
use Tribe\Project\Service_Providers\Settings_Provider;
use Tribe\Project\Service_Providers\Twig_Service_Provider;
use Tribe\Project\Service_Providers\Util_Provider;

class Core {

	protected static $_instance;

	/** @var Container */
	protected $container = null;

	/**
	 * @param Container $container
	 */
	public function __construct( $container ) {
		$this->container = $container;
	}

	public function init() {
		$this->load_libraries();
		$this->load_functions();
		$this->load_service_providers();
	}

	private function load_libraries() {
		require_once( dirname( $this->container[ 'plugin_file' ] ) . '/vendor/johnbillion/extended-cpts/extended-cpts.php' );
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
		$this->container->register( new Panels_Provider() );
		$this->container->register( new P2P_Provider() );
		$this->container->register( new Nav_Menu_Provider() );
		$this->container->register( new Settings_Provider() );
		$this->container->register( new Util_Provider() );
		$this->container->register( new Twig_Service_Provider() );
		$this->container->register( new Shortcode_Provider() );
		$this->container->register( new Object_Meta_Provider() );
		$this->container->register( new CLI_Provider() );

		$this->load_post_type_providers();
		$this->load_taxonomy_providers();
	}

	private function load_post_type_providers() {
		$this->container->register( new Sample_Post_Type_Service_Provider() );

		// externally registered post types
		$this->container->register( new Event_Service_Provider() );
		$this->container->register( new Organizer_Service_Provider() );
		$this->container->register( new Page_Service_Provider() );
		$this->container->register( new Post_Service_Provider() );
		$this->container->register( new Venue_Service_Provider() );
	}

	private function load_taxonomy_providers() {
		$this->container->register( new Example_Taxonomy_Service_Provider() );

		// externally registered taxonomies
		$this->container->register( new Category_Service_Provider() );
		$this->container->register( new Post_Tag_Service_Provider() );
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
