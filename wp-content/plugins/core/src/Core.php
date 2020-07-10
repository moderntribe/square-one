<?php

namespace Tribe\Project;

use Psr\Container\ContainerInterface;
use Tribe\Project\Admin\Admin_Subscriber;
use Tribe\Project\Assets\Assets_Subscriber;
use Tribe\Project\Blocks\Blocks_Definer;
use Tribe\Project\Blocks\Blocks_Subscriber;
use Tribe\Project\Cache\Cache_Subscriber;
use Tribe\Project\Components\Components_Definer;
use Tribe\Project\Components\Components_Subscriber;
use Tribe\Project\Integrations\Google_Tag_Manager\Google_Tag_Manager_Subscriber;
use Tribe\Project\Integrations\Gravity_Forms\Gravity_Forms_Subscriber;
use Tribe\Project\Integrations\Yoast_SEO\Yoast_SEO_Definer;
use Tribe\Project\Integrations\Yoast_SEO\Yoast_SEO_Subscriber;
use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Nav_Menus\Nav_Menus_Subscriber;
use Tribe\Project\Object_Meta\Object_Meta_Definer;
use Tribe\Project\P2P\P2P_Definer;
use Tribe\Project\Post_Types;
use Tribe\Project\Router\Router_Definer;
use Tribe\Project\Router\Router_Subscriber;
use Tribe\Project\Settings\Settings_Definer;
use Tribe\Project\Shortcodes\Shortcodes_Subscriber;
use Tribe\Project\Taxonomies;
use Tribe\Project\Templates\Templates_Definer;
use Tribe\Project\Templates\Templates_Subscriber;
use Tribe\Project\Theme\Theme_Definer;
use Tribe\Project\Theme\Theme_Subscriber;

class Core {
	public const PLUGIN_FILE = 'plugin.file';

	/**
	 * @var self
	 */
	private static $instance;

	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var string[] Names of classes implementing Definer_Interface
	 */
	private $definers = [
		Blocks_Definer::class,
		Nav_Menus_Definer::class,
		Object_Meta_Definer::class,
		P2P_Definer::class,
		Settings_Definer::class,
		Templates_Definer::class,
		Theme_Definer::class,
		Yoast_SEO_Definer::class,
		Components_Definer::class,
//		Router_Definer::class,
	];

	/**
	 * @var string[] Names of classes extending Abstract_Subscriber
	 */
	private $subscribers = [
		Admin_Subscriber::class,
		Assets_Subscriber::class,
		Blocks_Subscriber::class,
		Cache_Subscriber::class,
		Google_Tag_Manager_Subscriber::class,
		Gravity_Forms_Subscriber::class,
		Nav_Menus_Subscriber::class,
		Shortcodes_Subscriber::class,
		Theme_Subscriber::class,
		Templates_Subscriber::class,
		Yoast_SEO_Subscriber::class,
//		Router_Subscriber::class,
		Components_Subscriber::class,

		// our post types
		Post_Types\Sample\Subscriber::class,

		// our taxonomies
		Taxonomies\Example\Subscriber::class,
	];

	/**
	 * @var array Names of classes from Tribe Libs implementing Definer_Interface
	 */
	private $lib_definers = [
		'\Tribe\Libs\Assets\Assets_Definer',
		'\Tribe\Libs\Blog_Copier\Blog_Copier_Definer',
		'\Tribe\Libs\Cache\Cache_Definer',
		'\Tribe\Libs\CLI\CLI_Definer',
		'\Tribe\Libs\Generators\Generator_Definer',
		'\Tribe\Libs\Object_Meta\Object_Meta_Definer',
		'\Tribe\Libs\P2P\P2P_Definer',
		'\Tribe\Libs\Queues\Queues_Definer',
		'\Tribe\Libs\Queues_Mysql\Mysql_Backend_Definer',
		'\Tribe\Libs\Required_Page\Required_Page_Definer',
		'\Tribe\Libs\Settings\Settings_Definer',
		'\Tribe\Libs\Twig\Twig_Definer',
		'\Tribe\Libs\Whoops\Whoops_Definer',
	];

	/**
	 * @var array Names of classes from Tribe Libs extending Abstract_Subscriber
	 */
	private $lib_subscribers = [
		'\Tribe\Libs\Blog_Copier\Blog_Copier_Subscriber',
		'\Tribe\Libs\Cache\Cache_Subscriber',
		'\Tribe\Libs\CLI\CLI_Subscriber',
		'\Tribe\Libs\Media\Media_Subscriber',
		'\Tribe\Libs\Object_Meta\Object_Meta_Subscriber',
		'\Tribe\Libs\P2P\P2P_Subscriber',
		'\Tribe\Libs\Queues\Queues_Subscriber',
		'\Tribe\Libs\Queues_Mysql\Mysql_Backend_Subscriber',
		'\Tribe\Libs\Required_Page\Required_Page_Subscriber',
		'\Tribe\Libs\Settings\Settings_Subscriber',
		'\Tribe\Libs\Whoops\Whoops_Subscriber',
	];


	public function init( string $plugin_path ) {
		$this->init_container( $plugin_path );
	}

	private function init_container( string $plugin_path ): void {

		// combine definers/subscribers from the project and libs
		$definers    = array_merge( array_filter( $this->lib_definers, 'class_exists' ), $this->definers );
		$subscribers = array_merge( array_filter( $this->lib_subscribers, 'class_exists' ), $this->subscribers );

		/**
		 * Filter the list of definers that power the plugin
		 *
		 * @param string[] $definers The class names of definers that will be instantiated
		 */
		$definers = apply_filters( 'tribe/project/definers', $definers );

		/**
		 * Filter the list subscribers that power the plugin
		 *
		 * @param string[] $subscribers The class names of subscribers that will be instantiated
		 */
		$subscribers = apply_filters( 'tribe/project/subscribers', $subscribers );

		$builder = new \DI\ContainerBuilder();
		$builder->useAutowiring( true );
		$builder->useAnnotations( false );
		$builder->addDefinitions( [ self::PLUGIN_FILE => $plugin_path ] );
		$builder->addDefinitions( ... array_map( function ( $classname ) {
			return ( new $classname() )->define();
		}, $definers ) );

		$this->container = $builder->build();

		foreach ( $subscribers as $subscriber_class ) {
			( new $subscriber_class( $this->container ) )->register();
		}
	}

	public function container(): ContainerInterface {
		return $this->container;
	}

	/**
	 * @return self
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
