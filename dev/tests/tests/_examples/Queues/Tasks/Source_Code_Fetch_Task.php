<?php

namespace Tribe\Project\Queues\Tasks;

use Monolog\Logger;
use Tribe\Project\Exceptions\SourceCodeFetchException;
use Tribe\Project\Object_Meta\Post_Types\Website_Submission;
use Tribe\Project\Queues\Contracts\Task;

/**
 * Class Source_Code_Fetch
 *
 * This is just a example Task that is going to be tested.
 *
 * @package Tribe\Project\Queues\Tasks
 */
class Source_Code_Fetch implements Task {
	/**
	 * When fetching the source code from an external URL, we might get
	 * some overwhemingly big responses. To avoid issues and database
	 * cluttering, We'll be adding a max size for the source code.
	 *
	 * The default is 2e6 (2 million) characters, most websites
	 * have between 50k~200k.
	 */
	private $source_code_max_size;

	/**
	 * @var int $post_id Post ID that we will be adding the source code to.
	 */
	private $post_id;

	/**
	 * @var string $url URL of the website we will fetch the source code from.
	 */
	private $url;

	/**
	 * @var string $source_code Source code that was fetched from the URL.
	 */
	private $source_code;

	/**
	 * @var bool $should_log Whether it should log it if fails or not.
	 */
	private $should_log;

	public function __construct() {
		$this->should_log           = (bool) apply_filters( 'tribe_source_code_fetch_task_should_log', true );
		$this->source_code_max_size = (int) apply_filters( 'tribe_source_code_fetch_task_max_size', 2e6 ); // 2M
	}

	/**
	 * @param array $args
	 *
	 * @return bool
	 */
	public function handle( array $args ): bool {
		try {
			$this->validateRequest( $args );
			$this->fetch();
			$this->updatePost();

			return true;
		} catch ( SourceCodeFetchException $e ) {
			if ( $this->should_log ) {
				$log_message = sprintf(
					"Could not process Source Code Fetch task: %s (%s)",
					$e->getMessage(),
					$e->getCode()
				);
				tribe_project()->container()['util.logger']->log( Logger::ERROR, $log_message );
			}

			return false;
		}
	}

	/**
	 * @param array $args
	 *
	 * @throws SourceCodeFetchException
	 */
	protected function validateRequest( array $args ) {
		if ( empty( $args['post_id'] ) ) {
			throw SourceCodeFetchException::post_id_empty();
		}

		$url = get_post_meta( $args['post_id'], Website_Submission::URL, true );

		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			throw SourceCodeFetchException::invalid_url();
		}

		$this->post_id = $args['post_id'];
		$this->url     = $url;
	}

	/**
	 * @throws SourceCodeFetchException
	 * @return string
	 */
	protected function fetch() {
		$args           = apply_filters( 'tribe_source_code_fetch_args', [] );
		$response       = wp_remote_get( $this->url, $args );
		$content_length = wp_remote_retrieve_header( $response, 'content-length' );
		$response_code  = wp_remote_retrieve_response_code( $response );
		$response_body  = wp_remote_retrieve_body( $response );

		if ( $response instanceof \WP_Error ) {
			throw SourceCodeFetchException::unexpected_error_response( $response );
		}

		if ( $response_code !== 200 ) {
			throw SourceCodeFetchException::bad_status_code( $response_code );
		}

		if ( $content_length > $this->source_code_max_size ) {
			throw SourceCodeFetchException::exceeds_maximum_size( $content_length, $this->source_code_max_size );
		}

		$this->source_code = $response_body;
	}

	/**
	 * @throws SourceCodeFetchException
	 */
	protected function updatePost() {
		$result = update_post_meta( $this->post_id, Website_Submission::SOURCE_CODE, $this->source_code );

		if ( $result === false ) {
			throw SourceCodeFetchException::could_not_update_post_meta_with_source_code();
		}
	}
}