<?php

namespace Tribe\Project;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Container_Provider;
use Tribe\Project\Admin\Admin_Subscriber;
use Tribe\Project\Cache\Cache_Provider;
use Tribe\Project\Container\Subscriber_Interface;
use Tribe\Project\Development\Whoops_Subscriber;
use Tribe\Project\Nav_Menus\Nav_Menus_Subscriber;
use Tribe\Project\Object_Meta\Object_Meta_Subscriber;
use Tribe\Project\Panels\Panels_Subscriber;
use Tribe\Project\Service_Providers\CLI_Provider;
use Tribe\Project\Service_Providers\Content_Provider;
use Tribe\Project\Service_Providers\Post_Types\Event_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Organizer_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Page_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Post_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Sample_Service_Provider;
use Tribe\Project\Service_Providers\Post_Types\Venue_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Category_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Example_Taxonomy_Service_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Post_Tag_Service_Provider;
use Tribe\Project\Service_Providers\Twig_Service_Provider;
use Tribe\Project\Settings\Settings_Subscriber;
use Tribe\Project\Shortcodes\Shortcodes_Subscriber;
use Tribe\Project\Templates\Templates_Subscriber;
use Tribe\Project\Theme\Theme_Subscriber;
use Tribe\Project\Theme_Customizer\Theme_Customizer_Subscriber;

class Core {

	protected static $_instance;

	/** @var \Pimple\Container */
	protected $container = null;

	/** @var ContainerInterface */
	protected $template_container;

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
		$this->init_template_container();
	}

	private function load_service_providers() {
		$this->providers['container'] = new Container_Provider();

		// keep these in alphabetical order, it makes the list easier to skim
		$this->providers['cache']   = new Cache_Provider(); // override tribe-libs default
		$this->providers['cli']     = new CLI_Provider();
		$this->providers['content'] = new Content_Provider();
		//$this->providers['p2p']              = new P2P_Provider();
		$this->providers['twig'] = new Twig_Service_Provider();

		$this->optional_dependencies();
		$this->load_post_type_providers();
		$this->load_taxonomy_providers();

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

	private function init_template_container(): void {
		/**
		 * @var Subscriber_Interface[][] List of definition files (keys) and their corresponding subscribers (values)
		 */
		$definitions = [
			dirname( __DIR__ ) . '/definitions/admin.php'            => [ Admin_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/assets.php'           => [],
			dirname( __DIR__ ) . '/definitions/nav-menus.php'        => [ Nav_Menus_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/object-meta.php'      => [ Object_Meta_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/panels.php'           => [ Panels_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/settings.php'         => [ Settings_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/shortcodes.php'       => [ Shortcodes_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/theme.php'            => [ Theme_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/theme-customizer.php' => [ Theme_Customizer_Subscriber::class ],
			dirname( __DIR__ ) . '/definitions/twig.php'             => [],
			dirname( __DIR__ ) . '/definitions/templates.php'        => [ Templates_Subscriber::class ],
		];

		if ( defined( 'WHOOPS_ENABLE' ) && WHOOPS_ENABLE && class_exists( '\Whoops\Run' ) ) {
			$definitions[ dirname( __DIR__ ) . '/definitions/whoops.php' ] = [ Whoops_Subscriber::class ];
		}

		$builder = new \DI\ContainerBuilder();
		$builder->addDefinitions( [ 'plugin.file' => dirname( __DIR__ ) . '/core.php' ] );
		$builder->addDefinitions( ... array_keys( $definitions ) );

		$this->template_container = $builder->build();

		foreach ( $definitions as $definition_file => $subscribers ) {
			foreach ( $subscribers as $subscriber ) {
				$this->template_container->get( $subscriber )->register( $this->template_container );
			}
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
			if ( class_exists( $provider ) ) {
				$this->providers[ $key ] = new $provider();
			}
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

	public function template_container(): ContainerInterface {
		return $this->template_container;
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
