<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Blog_Copier\Network_Admin_Screen;
use Tribe\Project\Blog_Copier\Strategies\Recursive_File_Copy;
use Tribe\Project\Blog_Copier\Strategies\Shell_File_Copy;
use Tribe\Project\Blog_Copier\Task_Chain;
use Tribe\Project\Blog_Copier\Tasks\Cleanup;
use Tribe\Project\Blog_Copier\Tasks\Copy_Files;
use Tribe\Project\Blog_Copier\Tasks\Create_Blog;
use Tribe\Project\Blog_Copier\Tasks\Mark_Complete;
use Tribe\Project\Blog_Copier\Tasks\Replace_Guids;
use Tribe\Project\Blog_Copier\Tasks\Replace_Options;
use Tribe\Project\Blog_Copier\Tasks\Replace_Tables;
use Tribe\Project\Blog_Copier\Tasks\Replace_Urls;
use Tribe\Project\Blog_Copier\Tasks\Send_Notifications;

class Blog_Copier_Provider implements ServiceProviderInterface {
	const ADMIN_SCREEN       = 'blog_copier.admin_screen';
	const MANAGER            = 'blog_copier.manager';
	const CHAIN              = 'blog_copier.chain';
	const FILE_COPY_STRATEGY = 'blog_copier.file_copy_strategy';

	public function register( Container $container ) {
		$container[ self::ADMIN_SCREEN ] = function ( Container $container ) {
			return new Network_Admin_Screen();
		};

		add_action( 'network_admin_menu', function () use ( $container ) {
			$container[ self::ADMIN_SCREEN ]->register_screen();
		}, 10, 0 );
		add_action( 'network_admin_edit_' . Network_Admin_Screen::NAME, function () use ( $container ) {
			$container[ self::ADMIN_SCREEN ]->handle_submission();
		}, 10, 0 );

		$container[ self::MANAGER ] = function ( Container $container ) {
			return new Copy_Manager( $container[ Queues_Provider::DEFAULT_QUEUE ], $container[ self::CHAIN ] );
		};

		add_action( 'tribe/project/copy-blog/copy', function ( Copy_Configuration $configuration ) use ( $container ) {
			$container[ self::MANAGER ]->initialize( $configuration );
		}, 10, 1 );
		add_action( Copy_Manager::TASK_COMPLETE_ACTION, function ( $completed_task, $args ) use ( $container ) {
			$container[ self::MANAGER ]->schedule_next_step( $completed_task, $args );
		}, 10, 2 );

		$container[ self::CHAIN ] = function ( Container $container ) {
			return new Task_Chain( [
				Create_Blog::class,
				Replace_Tables::class,
				Replace_Options::class,
				Replace_Guids::class,
				Copy_Files::class,
				Replace_Urls::class,
				Mark_Complete::class,
				Send_Notifications::class,
				Cleanup::class,
			] );
		};

		$container[ self::FILE_COPY_STRATEGY ] = function ( Container $container ) {
			return new Shell_File_Copy();

			// If the hosting environment does not support exec(), use this instead
			// return new Recursive_File_Copy();
		};

		add_action( 'tribe/project/copy-blog/copy-files', function ( $src, $dest ) use ( $container ) {
			$container[ self::FILE_COPY_STRATEGY ]->handle_copy( $src, $dest );
		}, 10, 2 );
	}
}