<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

class Send_Notifications implements Task {

	public function handle( array $args ): bool {
		$success = true;
		$post_id = empty( $args[ 'post_id' ] ) ? 0 : absint( $args[ 'post_id' ] );

		$data = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );

		if ( ! empty( $data[ 'notify' ] ) ) {
			$blog_title          = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';
			$destination_blog_id = absint( get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true ) );

			$to      = $data[ 'notify' ];
			$subject = __( 'Blog Copy Complete', 'tribe' );
			$message = sprintf( __( 'Finished copying data to new blog: %s', 'tribe' ), wp_strip_all_tags( $blog_title ) ) . "\n\n";
			$message .= esc_url( get_home_url( $destination_blog_id ) ) . "\n\n";

			try {
				$success = wp_mail( $to, $subject, $message );
			} catch ( \Exception $e ) {
				$success = false;
			}
		}

		if ( $success ) {
			do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );
		}

		return $success;
	}

}