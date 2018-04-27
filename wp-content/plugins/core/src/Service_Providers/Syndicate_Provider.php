<?php

namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Syndicate\Admin\Copier;
use Tribe\Project\Syndicate\Admin\Posts;
use Tribe\Project\Syndicate\Admin\Settings;
use Tribe\Project\Syndicate\Admin\Settings_Fields;
use Tribe\Project\Syndicate\Blog;
use Tribe\Project\Syndicate\Content;
use Tribe\Project\Syndicate\Cross_Index;
use Tribe\Project\Syndicate\Query;

class Syndicate_Provider implements ServiceProviderInterface {
	const BLOG                  = 'syndicate.blog';
	const CONTENT               = 'syndicate.content';
	const CROSS_INDEX           = 'syndicate.cross_index';
	const QUERY                 = 'syndicate.query';
	const ADMIN_COPIER          = 'syndicate.admin.copier';
	const ADMIN_POSTS           = 'syndicate.admin.posts';
	const ADMIN_SETTINGS        = 'syndication.settings';
	const ADMIN_SETTINGS_FIELDS = 'syndication.settings.fields';

	public function register( Container $container ) {

		$this->register_settings_page( $container );
		$this->content_filter( $container );

		$container[ self::BLOG ] = function () {
			return new Blog();
		};

		$container[ self::QUERY ] = function () use ( $container ) {
			return new Query( $container[ self::BLOG ] );
		};

		$container[ self::ADMIN_POSTS ] = function () use ( $container ) {
			return new Posts( $container[ self::BLOG ] );
		};

		$container[ self::ADMIN_COPIER ] = function () use ( $container ) {
			return new Copier( $container[ self::BLOG ] );
		};

		$container[ self::CROSS_INDEX ] = function () use ( $container ) {
			return new Cross_Index();
		};

		$container[ self::CONTENT ] = function () use ( $container ) {
			return new Content();
		};

		add_filter( 'dbdelta_create_queries', function ( $queries ) use ( $container ) {
			return $container[ self::BLOG ]->table_creation( $queries );
		} );

		add_filter( 'wpmu_drop_tables', function( $tables, $blog_id ) use( $container ) {
			return $container[ self::BLOG ]->drop_views( $tables, $blog_id );
		}, 10, 2 );

		add_action( 'deleted_blog', function( int $blog_id ) use( $container ) {
			$container[ self::BLOG ]->prune_syndicated_table( $blog_id );
		} );

		// We look at each query.
		add_filter( 'query', function ( $query ) use ( $container ) {
			return $container[ self::QUERY ]->query( $query );
		} );

		add_action( 'init', function () use ( $container ) {
			$container[ self::BLOG ]->register_tables();
		} );

		add_filter( 'post_row_actions', function ( $actions, $post ) use ( $container ) {
			return $container[ self::ADMIN_POSTS ]->post_row_actions( $actions, $post );
		}, 10, 2 );

		add_filter( 'admin_post_copy_to_blog', function () use ( $container ) {
			return $container[ self::ADMIN_COPIER ]->copy_to_blog();
		} );

		add_filter( 'admin_post_remove_copied_post', function () use ( $container ) {
			return $container[ self::ADMIN_COPIER ]->remove_copied_post();
		} );

		add_filter( 'map_meta_cap', function ( $caps, $cap, $user_id, $args ) use ( $container ) {
			return $container[ self::ADMIN_POSTS ]->disable_edit( $caps, $cap, $user_id, $args );
		}, 10, 4 );

		add_action( 'ep_after_index_post', function ( ...$args ) use ( $container ) {
			if ( is_main_site() ) {
				$container[ self::CROSS_INDEX ]->post_indexed( ...$args );
			}
		}, 10, 99 );
	}

	private function register_settings_page( $container ) {
		$container[ self::ADMIN_SETTINGS ] = function ( Container $container ) {
			return new Settings();
		};
		add_action( 'init', function () use ( $container ) {
			$container[ self::ADMIN_SETTINGS ]->hook();
		}, 0, 0 );

		$container[ self::ADMIN_SETTINGS_FIELDS ] = function ( Container $container ) {
			return new Settings_Fields( [
				'settings_pages' => [ $container[ self::ADMIN_SETTINGS ]::instance()->get_slug() ],
			] );
		};

		add_action( 'init', function() use ( $container ) {
			$container[ Object_Meta_Provider::REPO ]->add_group( $container[ self::ADMIN_SETTINGS_FIELDS ] );
		}, 9, 0 );

		add_action( 'acf/init', function () use ( $container ) {
			$container[ self::ADMIN_SETTINGS_FIELDS ]->register_group();
		}, 10, 0 );

		add_action( 'acf/save_post', function () use ( $container ) {
			$container[ self::ADMIN_SETTINGS_FIELDS ]->option_update();
		}, 100, 3 );

		add_action( 'syndicate/alter_views', function () use ( $container ) {
			$container[ self::BLOG ]->alter_views();
		} );
	}

	private function content_filter( $container ) {
		add_filter( 'the_content', function ( $content ) use ( $container ) {
			return $container[ self::CONTENT ]->fix_site_links( $content );
		} );

		add_filter( 'wp_get_attachment_image_src', function ( $image, $attachment_id, $size, $icon ) use ( $container ) {
			return $container[ self::CONTENT ]->fix_attachment_image_src( $image, $attachment_id, $size, $icon );
		}, 10, 4);
	}

}
