<?php


namespace Tribe\Project\Service_Providers\Permissions;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Permissions\Object_Meta\Menu_Sections;
use Tribe\Project\Permissions\Object_Meta\Post_Sections;
use Tribe\Project\Permissions\Object_Meta\Section_Users;
use Tribe\Project\Permissions\Object_Meta\User_Sections;
use Tribe\Project\Permissions\Capabilities\Cap_Filter;
use Tribe\Project\Permissions\Capabilities\Nav_Admin;
use Tribe\Project\Permissions\Capabilities\Posts_Admin;
use Tribe\Project\Permissions\Roles\Role_Collection;
use Tribe\Project\Permissions\Roles\Section_Author;
use Tribe\Project\Permissions\Roles\Section_Contributor;
use Tribe\Project\Permissions\Roles\Section_Editor;
use Tribe\Project\Permissions\Roles\Section_Manager;
use Tribe\Project\Permissions\Section_Assigner;
use Tribe\Project\Permissions\Section_Switcher;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Post_Types\Event\Event;
use Tribe\Project\Post_Types\Organizer\Organizer;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Venue\Venue;
use Tribe\Project\Service_Providers\Object_Meta_Provider;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Permissions_Provider implements ServiceProviderInterface {
	const ROLE_FACTORY              = 'permissions.roles.factory';
	const ROLES                     = 'permissions.roles';
	const SECTION_CAPS              = 'permissions.capabilities';
	const SECTION_POST_TYPES        = 'permissions.post_types';
	const SECTION_EDITOR_POST_TYPES = 'permissions.editor_post_types';
	const SECTION_TAXONOMIES        = 'permissions.taxonomies';
	const USER_ROLE_META            = 'permissions.meta.users';
	const SECTION_ROLE_META         = 'permissions.meta.sections';
	const MENU_SECTION_META         = 'permissions.meta.menus';
	const POST_SECTION_META         = 'permissions.meta.posts';
	const SECTION_SWITCHER          = 'permissions.section_switcher';
	const SECTION_ASSIGNER          = 'permissions.section_assigner';
	const NAV_ADMIN                 = 'permissions.nav.admin';
	const POSTS_ADMIN               = 'permissions.posts.admin';

	public function register( Container $container ) {
		$container->register( new Section_Provider() );
		$this->roles( $container );
		$this->capabilities( $container );
		$this->object_meta( $container );
		$this->section_switcher( $container );
		$this->section_assigner( $container );
		$this->nav_admin( $container );
		$this->posts_admin( $container );
	}

	private function roles( Container $container ) {
		$container[ self::SECTION_EDITOR_POST_TYPES ] = function ( Container $container ) {
			return array_merge( $container[ self::SECTION_POST_TYPES ], [ Page::NAME ] );
		};

		$container[ self::SECTION_POST_TYPES ] = function ( Container $container ) {
			return [
				Post::NAME,
				Event::NAME,
				Organizer::NAME,
				Venue::NAME,
				'attachment',
			];
		};

		$container[ self::SECTION_TAXONOMIES ] = function ( Container $container ) {
			return [
				Category::NAME,
				Post_Tag::NAME,
			];
		};
		$container[ self::ROLES ]              = function ( Container $container ) {
			return [
				new Section_Manager( $container[ self::SECTION_EDITOR_POST_TYPES ], $container[ self::SECTION_TAXONOMIES ] ),
				new Section_Editor( $container[ self::SECTION_EDITOR_POST_TYPES ], $container[ self::SECTION_TAXONOMIES ] ),
				new Section_Author( $container[ self::SECTION_POST_TYPES ], $container[ self::SECTION_TAXONOMIES ] ),
				new Section_Contributor( $container[ self::SECTION_POST_TYPES ], $container[ self::SECTION_TAXONOMIES ] ),
			];
		};
		$container[ self::ROLE_FACTORY ]       = function ( Container $container ) {
			return new Role_Collection( ...$container[ self::ROLES ] );
		};
	}

	private function capabilities( Container $container ) {
		$container[ self::SECTION_CAPS ] = function ( Container $container ) {
			return new Cap_Filter( $container[ self::ROLE_FACTORY ] );
		};

		add_filter( 'user_has_cap', function ( ...$args ) use ( $container ) {
			return $container[ self::SECTION_CAPS ]->set_user_caps( ...$args );
		}, 10, 99 );
	}

	private function object_meta( Container $container ) {
		$container[ self::USER_ROLE_META ]    = function ( Container $container ) {
			return new User_Sections( [
				'users' => true,
			], $container[ self::ROLE_FACTORY ] );
		};
		$container[ self::SECTION_ROLE_META ] = function ( Container $container ) {
			return new Section_Users( [
				'taxonomies' => [ Section::NAME ],
			], $container[ self::ROLE_FACTORY ] );
		};
		$container[ self::MENU_SECTION_META ] = function ( Container $container ) {
			return new Menu_Sections( [ /* applies to nav menus only */ ] );
		};
		$container[ self::POST_SECTION_META ] = function ( Container $container ) {
			return new Post_Sections( [ 'all', 'attachment' ] );
		};

		add_action( 'acf/init', function () use ( $container ) {
			foreach (
				[
					$container[ self::USER_ROLE_META ],
					$container[ self::SECTION_ROLE_META ],
					$container[ self::MENU_SECTION_META ],
					$container[ self::POST_SECTION_META ],
				] as $meta
			) {
				$container[ Object_Meta_Provider::REPO ]->add_group( $meta );
				$meta->register_group();
			}
		}, 10, 0 );
	}

	private function section_switcher( Container $container ) {
		$container[ self::SECTION_SWITCHER ] = function ( Container $container ) {
			return new Section_Switcher();
		};
		add_action( 'in_admin_header', function () use ( $container ) {
			$container[ self::SECTION_SWITCHER ]->print_section_switcher();
		} );

		add_action( 'admin_post_' . Section_Switcher::ACTION, function () use ( $container ) {
			$container[ self::SECTION_SWITCHER ]->handle_request();
		} );

		add_action( 'admin_print_styles', function () use ( $container ) {
			$container[ self::SECTION_SWITCHER ]->print_styles();
		} );

		add_filter( 'add_menu_classes', function ( $menu ) use ( $container ) {
			return $container[ self::SECTION_SWITCHER ]->add_menu_classes( $menu );
		}, 10, 1 );
	}

	private function section_assigner( Container $container ) {
		$container[ self::SECTION_ASSIGNER ] = function ( Container $container ) {
			return new Section_Assigner();
		};
		add_action( 'save_post', function ( $post_id, $post ) use ( $container ) {
			$container[ self::SECTION_ASSIGNER ]->assign_section_to_post( $post_id, $post );
		}, 100, 2 );
		add_action( 'attachment_updated', function ( $post_id, $post ) use ( $container ) {
			$container[ self::SECTION_ASSIGNER ]->assign_section_to_post( $post_id, $post );
		}, 100, 2 );
		add_action( 'add_attachment', function ( $post_id ) use ( $container ) {
			$container[ self::SECTION_ASSIGNER ]->assign_section_to_post( $post_id, get_post( $post_id ) );
		}, 100, 1 );
		add_action( 'wp_create_nav_menu', function ( $menu_id ) use ( $container ) {
			$container[ self::SECTION_ASSIGNER ]->assign_section_to_menu( $menu_id );
		}, 100, 1 );
		add_action( 'wp_update_nav_menu', function ( $menu_id ) use ( $container ) {
			$container[ self::SECTION_ASSIGNER ]->assign_section_to_menu( $menu_id );
		}, 100, 1 );
	}

	private function nav_admin( Container $container ) {
		$container[ self::NAV_ADMIN ] = function ( Container $container ) {
			return new Nav_Admin();
		};

		add_action( 'admin_menu', function () use ( $container ) {
			$container[ self::NAV_ADMIN ]->filter_admin_menu();
		} );
		$nav_admin_hooks = function () use ( $container ) {
			$container[ self::NAV_ADMIN ]->deregister_theme_menu_locations();
			add_filter( 'user_has_cap', [ $container[ self::NAV_ADMIN ], 'filter_nav_menu_caps' ], 12, 4 );
			add_filter( 'wp_get_nav_menus', [ $container[ self::NAV_ADMIN ], 'filter_available_nav_menus' ], 10, 2 );
			add_filter( 'get_user_option_nav_menu_recently_edited', [
				$container[ self::NAV_ADMIN ],
				'filter_recently_edited_nav_menu_option',
			], 10, 1 );
		};
		add_action( 'load-nav-menus.php', $nav_admin_hooks, 0, 0 );
		add_action( 'wp_ajax_add-menu-item', $nav_admin_hooks, 0, 0 );
	}

	private function posts_admin( Container $container ) {
		$container[ self::POSTS_ADMIN ] = function ( Container $container ) {
			return new Posts_Admin();
		};
		add_action( 'load-edit.php', function () use ( $container ) {
			add_action( 'pre_get_posts', function ( $query ) use ( $container ) {
				$container[ self::POSTS_ADMIN ]->filter_other_sections_from_list_tables( $query );
			} );
		} );
	}

}