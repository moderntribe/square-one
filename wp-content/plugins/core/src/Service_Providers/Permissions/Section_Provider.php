<?php


namespace Tribe\Project\Service_Providers\Permissions;


use Pimple\Container;
use Tribe\Project\Permissions\Object_Meta\Default_Section;
use Tribe\Project\Permissions\Object_Meta\Section_Properties;
use Tribe\Project\Permissions\Taxonomies\Section;
use Tribe\Project\Post_Types\Event\Event;
use Tribe\Project\Post_Types\Organizer\Organizer;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Venue\Venue;
use Tribe\Project\Service_Providers\Object_Meta_Provider;
use Tribe\Project\Service_Providers\Taxonomies\Taxonomy_Service_Provider;

class Section_Provider extends Taxonomy_Service_Provider {
	const DEFAULT_SECTION    = 'taxonomy.section.default';
	const TABLES             = 'taxonomy.section.tables';
	const ADMIN_MENU         = 'taxonomy.section.admin_menu';
	const SECTION_PROPERTIES = 'taxonomy.section.properties';

	protected $taxonomy_class = Section\Section::class;
	protected $config_class   = Section\Config::class;

	protected $post_types = [
		// this is going to apply to _everything_, but it will appear as its own top-level menu item
		Page::NAME,
		Post::NAME,
		Event::NAME,
		Organizer::NAME,
		Venue::NAME,
	];

	public function register( Container $container ) {
		parent::register( $container );

		$this->register_settings( $container );
		$this->register_tables( $container );
		$this->register_admin_menu( $container );
	}

	private function register_settings( Container $container ) {
		$container[ self::DEFAULT_SECTION ] = function ( Container $container ) {
			return new Default_Section( [ 'settings_pages' => [ 'writing' ] ] );
		};

		add_action( 'admin_init', function () use ( $container ) {
			$container[ self::DEFAULT_SECTION ]->register_group();
		}, 10, 0 );

		add_filter( 'default_option_' . Default_Section::TERM_ID, function ( ...$args ) use ( $container ) {
			return $container[ self::DEFAULT_SECTION ]->set_default_default_term_id( ... $args );
		}, 10, 99 );

		add_action( 'delete_' . Section\Section::NAME, function ( ...$args ) use ( $container ) {
			$container[ self::DEFAULT_SECTION ]->unset_option_when_term_deleted( ...$args );
		}, 10, 99 );

		$container[ self::SECTION_PROPERTIES ] = function ( Container $container ) {
			return new Section_Properties( [
				'taxonomies' => [ Section\Section::NAME ],
			] );
		};

		add_action( 'acf/init', function () use ( $container ) {
			foreach ( [ $container[ self::SECTION_PROPERTIES ] ] as $meta ) {
				$container[ Object_Meta_Provider::REPO ]->add_group( $meta );
				$meta->register_group();
			}
		}, 10, 0 );
	}

	private function register_tables( Container $container ) {
		$container[ self::TABLES ] = function ( Container $container ) {
			return new \Tribe\Project\Permissions\Schema\User_Section_Table();
		};

		add_action( 'plugins_loaded', function () use ( $container ) {
			$container[ self::TABLES ]->register_tables();
		}, 10, 0 );
	}

	private function register_admin_menu( Container $container ) {
		$container[ self::ADMIN_MENU ] = function ( Container $container ) {
			return new Section\Admin_Menu();
		};

		add_action( 'admin_menu', function () use ( $container ) {
			$container[ self::ADMIN_MENU ]->register_admin_menu();
		} );
	}


}