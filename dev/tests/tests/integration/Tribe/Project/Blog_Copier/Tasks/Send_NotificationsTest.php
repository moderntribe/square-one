<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;

class Send_NotificationsTest extends \Codeception\TestCase\WPTestCase {

	public function test_sends_emails() {
		/** @var \WP_User $user */
		$user    = $this->factory()->user->create_and_get();
		$config  = new Copy_Configuration( [
			'src'     => 2,
			'address' => 'dest',
			'title'   => 'Copy Destination',
			'files'   => true,
			'notify'  => 'alpha@example.com,beta@example.com',
			'user'    => $user->ID,
		] );
		$post_id = $this->factory()->post->create( [
			'post_type'    => Copy_Manager::POST_TYPE,
			'post_status'  => 'publish',
			'post_content' => \json_encode( $config ),
		] );

		update_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, 3 );

		$task = new Send_Notifications();
		$task->handle( [
			'post_id' => $post_id,
		] );

		/** @var \MockPHPMailer $mailer */
		$mailer = tests_retrieve_phpmailer_instance();
		$sent = $mailer->get_sent();

		$this->assertEqualSets( [ 'alpha@example.com', 'beta@example.com' ], array_column( $sent->to, 0 ) );
		$this->assertEquals( 'Blog Copy Complete', $sent->subject );
		$this->assertContains( $config->get_title(), $sent->body );
	}
}