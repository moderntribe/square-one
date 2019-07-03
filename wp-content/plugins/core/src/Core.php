<?php

namespace Tribe\Project;

use Tribe\Libs\Container\Container_Provider;
use Tribe\Project\Service_Providers\Admin_Provider;
use Tribe\Project\Service_Providers\Asset_Provider;
use Tribe\Project\Cache\Cache_Provider;
use Tribe\Project\Service_Providers\CLI_Provider;
use Tribe\Project\Service_Providers\Content_Provider;
use Tribe\Project\Service_Providers\Object_Meta_Provider;
use Tribe\Project\Service_Providers\Nav_Menu_Provider;
use Tribe\Project\Service_Providers\Panels_Provider;
use Tribe\Project\Service_Providers\Post_Types\Event_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Organizer_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Page_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Post_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Sample_Service_Provider;
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
use Tribe\Project\Service_Providers\Whoops_Provider;

class Core {

	protected static $_instance;

	/** @var \Pimple\Container */
	protected $container = null;

	/**
	 * @var Container\Service_Provider[]
	 */
	private $providers = [];

	/**
	 * @param \Pimple\Container $container
	 */
	public function __construct( \Pimple\Container $container ) {
		$this->container = $container;
	}

	public function init() {
		$this->load_service_providers();
	}

	private function load_service_providers() {
		$this->providers['container'] = new Container_Provider();

		// keep these in alphabetical order, it makes the list easier to skim
		$this->providers['admin']            = new Admin_Provider();
		$this->providers['assets']           = new Asset_Provider();
		$this->providers['cache']            = new Cache_Provider(); // override tribe-libs default
		$this->providers['cli']              = new CLI_Provider();
		$this->providers['content']          = new Content_Provider();
		$this->providers['meta']             = new Object_Meta_Provider();
		$this->providers['nav_menu']         = new Nav_Menu_Provider();
		$this->providers['panels']           = new Panels_Provider();
		$this->providers['p2p']              = new P2P_Provider();
		$this->providers['settings']         = new Settings_Provider();
		$this->providers['shortcodes']       = new Shortcode_Provider();
		$this->providers['theme']            = new Theme_Provider();
		$this->providers['theme_customizer'] = new Theme_Customizer_Provider();
		$this->providers['twig']             = new Twig_Service_Provider();
		$this->providers['util']             = new Util_Provider();

		$this->optional_dependencies();
		$this->load_post_type_providers();
		$this->load_taxonomy_providers();

		// Enable Whoops error logging if required.
		if ( defined( 'WHOOPS_ENABLE' ) && WHOOPS_ENABLE && class_exists( '\Whoops\Run' ) ) {
			$this->providers['whoops'] = new Whoops_Provider();
		}

		/**
		 * Filter the service providers that power the plugin
		 *
		 * @param Container\Service_Provider[] $providers
		 */
		$this->providers = apply_filters( 'tribe/project/providers', $this->providers );

		foreach ( $this->providers as $provider ) {
			$this->container->register( $provider );
		}
	}


	/**
	 * Register optional dependencies if they exist. Override
	 * the service provider for the dependency to change its
	 * behavior.
	 */
	private function optional_dependencies() {
		$optional_dependencies = [
			'blog_copier'  => '\Tribe\Libs\Blog_Copier\Blog_Copier_Provider',
			'container'    => '\Tribe\Libs\Container\Container_Provider',
			'generators'   => '\Tribe\Libs\Generators\Generator_Provider',
			'queues'       => '\Tribe\Libs\Queues\Queues_Provider',
			'queues-mysql' => '\Tribe\Libs\Queues_Mysql\Mysql_Backend_Provider',
		];

		foreach ( $optional_dependencies as $key => $provider ) {
			$this->providers[ $key ] = new $provider();
		}
	}

	private function load_post_type_providers() {
		$this->providers['post_type.sample'] = new Sample_Service_Provider();

		// externally registered post types
		$this->providers['post_type.event']     = new Event_Service_Provider();
		$this->providers['post_type.organizer'] = new Organizer_Service_Provider();
		$this->providers['post_type.page']      = new Page_Service_Provider();
		$this->providers['post_type.post']      = new Post_Service_Provider();
		$this->providers['post_type.venue']     = new Venue_Service_Provider();
	}

	private function load_taxonomy_providers() {
		$this->providers['taxonomy.example'] = new Example_Taxonomy_Service_Provider();

		// externally registered taxonomies
		$this->providers['taxonomy.category'] = new Category_Service_Provider();
		$this->providers['taxonomy.post_tag'] = new Post_Tag_Service_Provider();
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
