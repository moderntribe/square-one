<?php


namespace Tribe\Project\Blog_Copier;

use Tribe\Project\Blog_Copier\Tasks\Cleanup;
use Tribe\Project\Queues\Contracts\Queue;

class Copy_Manager {
	const TASK_COMPLETE_ACTION = 'tribe/project/copy-blog/task-complete';
	const TASK_ERROR_ACTION    = 'tribe/project/copy-blog/task-error';
	const POST_TYPE            = 'blog-copy';
	const TASK_LOG             = 'copy-blog-task-log';
	const DESTINATION_BLOG     = 'copy-blog-destination-id';
	const COPY_ERROR           = 'copy-blog-errors';

	/**
	 * @var Queue
	 */
	private $queue;
	/**
	 * @var Task_Chain
	 */
	private $chain;

	public function __construct( Queue $queue, Task_Chain $chain ) {
		$this->queue = $queue;
		$this->chain = $chain;
	}

	/**
	 * @param Copy_Configuration $configuration
	 *
	 * @return int
	 * @action tribe/project/copy-blog/copy
	 */
	public function initialize( Copy_Configuration $configuration ) {
		$post_id = $this->create_state_post( $configuration );
		$this->create_first_task( $post_id );

		return $post_id;
	}

	private function create_state_post( Copy_Configuration $configuration ) {
		$post_id = wp_insert_post( [
			'post_type'    => self::POST_TYPE,
			'post_status'  => 'pending',
			'post_title'   => $configuration->get_title(),
			'post_content' => json_encode( $configuration ),
			'post_author'  => $configuration->get_user(),
		] );

		return $post_id;
	}

	private function create_first_task( $post_id ) {
		$task = $this->chain->get_first();
		if ( $task ) {
			$this->queue->dispatch( $task, [ 'post_id' => $post_id ] );
		}
	}

	/**
	 * Schedule the next task for processing this copy
	 *
	 * @param string $completed_task
	 * @param array  $args
	 *
	 * @return void
	 * @action self::TASK_COMPLETE_ACTION
	 */
	public function schedule_next_step( $completed_task, $args ) {
		if ( empty( $args['post_id'] ) ) {
			return;
		}
		add_post_meta( $args['post_id'], self::TASK_LOG, \json_encode( [
			'timestamp' => time(),
			'task'      => $completed_task,
		] ) );
		$next = $this->chain->get_next( $completed_task );
		if ( ! empty( $next ) ) {
			if ( $next === Cleanup::class ) {
				$args['run_after'] = ( new \DateTime( '+1 week' ) )->format( 'Y-m-d H:i:s' );
			}
			$this->queue->dispatch( $next, $args );
		}
		if ( class_exists( 'WP_CLI' ) ) {
			\WP_CLI::debug( sprintf( 'Completed blog copy task: %s', $completed_task ) );
		}
	}

	/**
	 * @param string    $failed_task
	 * @param array     $args
	 * @param \WP_Error $error
	 *
	 * @return void
	 */
	public function mark_error( $failed_task, $args, $error ) {
		if ( empty( $args['post_id'] ) ) {
			return;
		}
		update_post_meta( $args['post_id'], self::COPY_ERROR, $error->get_error_message() );
		wp_trash_post( $args['post_id'] );
		if ( class_exists( 'WP_CLI' ) ) {
			\WP_CLI::debug( sprintf( 'Error in blog copy task: %s.', $failed_task ) );
			\WP_CLI::debug( sprintf( 'Error message: %s.', $error->get_error_message() ) );
		}
	}
}
