<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Content\Contact_Page;

class Content_Provider implements ServiceProviderInterface {
	const REQUIRED_PAGES = 'content.required_pages';

	const CONTACT_PAGE = 'content.contact_page';

	public function register( Container $container ) {
		$container[ self::REQUIRED_PAGES ] = function ( $container ) {
			return [
				// $container[ self::CONTACT_PAGE ],
			];
		};

		$container[ self::CONTACT_PAGE ] = function ( Container $container ) {
			$example_meta = $container[ Object_Meta_Provider::EXAMPLE ];
			$group        = $example_meta->get_group_config();
			$key          = $group[ 'key' ];

			return new Contact_Page( $key );
		};

		add_action( 'init', function () use ( $container ) {
			foreach ( $container[ self::REQUIRED_PAGES ] as $page ) {
				add_action( 'admin_init', [ $page, 'ensure_page_exists' ], 10, 0 );
				add_action( 'trashed_post', [ $page, 'clear_option_on_delete' ], 10, 1 );
				add_action( 'deleted_post', [ $page, 'clear_option_on_delete' ], 10, 1 );
				add_action( 'acf/init', [ $page, 'register_setting' ], 10, 0 );
				add_filter( 'display_post_states', [ $page, 'indicate_post_state' ], 10, 2 );
			}
		}, 0, 0 );
	}
}