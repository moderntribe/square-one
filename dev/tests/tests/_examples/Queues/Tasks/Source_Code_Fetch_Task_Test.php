<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Object_Meta\Post_Types\Website_Submission;
use Tribe\Tests\SquareOneTestCase;

/**
 * Class Source_Code_Fetch_Task_Test
 *
 * This is just an example on how to test a Task class.
 *
 * @package Tribe\Project\Queues\Tasks
 */
class Source_Code_Fetch_Task_Test extends SquareOneTestCase {

	const TEMP_FILE = 'source_code_fetch.html';

	protected $temp_url;
	protected $temp_path;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->create_temp_test_file();

		add_filter( 'tribe_source_code_fetch_task_should_log', function () {
			return false;
		} );
	}

	public function tearDown() {
		// your tear down methods here
		$this->delete_temp_test_file();

		// then
		parent::tearDown();
	}

	/**
	 * Helper function to create a temp file that will serve as URL to be fetched by the tests
	 *
	 * @param string $filename
	 */
	protected function create_temp_test_file( $filename = self::TEMP_FILE ) {
		$wp_upload       = wp_upload_dir();
		$this->temp_path = $wp_upload['path'] . '/' . $filename;

		$content = '<html><body>I am valid HTML for the source code fetch task tests!</body></html>';

		file_put_contents( $this->temp_path, $content );

		$this->temp_url = $wp_upload['url'] . '/' . $filename;
	}

	/**
	 * Helper function to delete the temp file created in these tests
	 *
	 * @return bool
	 */
	protected function delete_temp_test_file() {
		if ( file_exists( $this->temp_path ) ) {
			unlink( $this->temp_path );
		}

		return ! file_exists( $this->temp_path );
	}

	/**
	 * Helper function to create a post and fetch the given URL using the Source Code Fetch class.
	 *
	 * @param string $url
	 *
	 * @return array
	 */
	protected function create_post_and_fetch_url( string $url ) : array {
		$post_id = $this->factory()->post->create();

		$did_update_url = update_post_meta( $post_id, Website_Submission::URL, $url );

		// Ask the task to fetch the source code.
		$task = new Source_Code_Fetch();

		add_filter( 'tribe_source_code_fetch_args', function ( $args ) {
			$args['sslverify'] = false;

			return $args;
		} );

		$handle_return = $task->handle( [ 'post_id' => $post_id ] );

		$task_source_code = get_post_meta( $post_id, Website_Submission::SOURCE_CODE, true );

		return [
			'did_update_url' => $did_update_url,
			'handle_return'  => $handle_return,
			'source_code'    => $task_source_code,
		];
	}

	/** @test */
	public function should_fetch_the_source_code_and_update_the_post() {
		$task_result = $this->create_post_and_fetch_url($this->temp_url);

		// Fetch the source code ourselves.
		$args             = apply_filters( 'tribe_source_code_fetch_args', [] );
		$response         = wp_remote_get( $this->temp_url, $args );
		$test_source_code = wp_remote_retrieve_body( $response );

		$this->assertNotFalse( $task_result['did_update_url'] );
		$this->assertTrue( $task_result['handle_return'] );
		$this->assertEquals( $test_source_code, $task_result['source_code'] );
	}

	/** @test */
	public function should_fail_if_max_size_exceeds() {
		add_filter( 'tribe_source_code_fetch_task_max_size', function () {
			return 1; // Max 1 character allowed
		} );

		$task_result = $this->create_post_and_fetch_url($this->temp_url);

		$this->assertNotFalse( $task_result['did_update_url'] );
		$this->assertFalse( $task_result['handle_return'] );
	}

	/** @test */
	public function should_fail_if_empty_args() {
		$task        = new Source_Code_Fetch();
		$task_result = $task->handle( [] );

		$this->assertFalse( $task_result );
	}

	/**
	 * @test
	 *
	 * @dataProvider provide_invalid_post_ids
	 *
	 * @param $invalid_post_id
	 *
	 * @throws \Exception
	 */
	public function should_fail_if_invalid_post_id( $invalid_post_id ) {
		$task        = new Source_Code_Fetch();
		$task_result = $task->handle( [ 'post_id' => $invalid_post_id ] );

		$this->assertFalse( $task_result );
	}

	public function provide_invalid_post_ids() {
		return [
			[ "" ],
			[ "not-a-number" ],
			[ 9999999999 ],
		];
	}

	/**
	 * @test
	 *
	 * @dataProvider provide_invalid_urls
	 *
	 * @param $invalid_url
	 *
	 * @throws \Exception
	 */
	public function should_fail_if_invalid_url( $invalid_url ) {
		$post_id = $this->factory()->post->create();

		$did_update_url = update_post_meta( $post_id, Website_Submission::URL, $invalid_url );

		// Ask the task to fetch the source code.
		$task = new Source_Code_Fetch();

		add_filter( 'tribe_source_code_fetch_args', function ( $args ) {
			$args['sslverify'] = false;

			return $args;
		} );

		$task_result = $task->handle( [ 'post_id' => $post_id ] );

		$this->assertNotFalse( $did_update_url );
		$this->assertFalse( $task_result );
	}

	public function provide_invalid_urls() {
		$invalid_urls = [
			[ "" ],
			[ "not-a-url" ],
			[ "ftp://google.com" ],
			[ "127.0.0.1" ],
		];

		$protocols_to_test = ["ftp", "ssh", "git"];

		$this->create_temp_test_file();

		foreach ($protocols_to_test as $p) {
			$invalid_protocol = preg_replace( "/^https?:/i", "$p:", $this->temp_url );
			$invalid_urls[]   = [ $invalid_protocol ];
		}

		$this->delete_temp_test_file();

		return $invalid_urls;
	}
}
