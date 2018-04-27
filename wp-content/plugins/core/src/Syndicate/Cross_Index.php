<?php


namespace Tribe\Project\Syndicate;

/**
 * Class Cross_Index
 *
 * Responsible for sending indexing requests for updated posts to all indices
 */
class Cross_Index {
	public function __construct() {

	}

	/**
	 * @param array $post_data
	 *
	 * @return void
	 * @action ep_after_index_post
	 */
	public function post_indexed( $post_data ) {
		// TODO: skip if a post that should not be indexed

		$encoded_post = wp_json_encode( $post_data );
		$bulk_data    = [];
		foreach ( $this->get_blog_indices() as $index ) {
			$bulk_data[] = json_encode( [
				'index' => [
					'_index' => $index,
					'_type'  => 'post',
					'_id'    => $post_data[ 'post_id' ],
				],
			] );
			$bulk_data[] = $encoded_post;
		}
		$request_args = [
			'body'     => implode( "\n", $bulk_data ) . "\n",
			'method'   => 'POST',
			'timeout'  => 15,
			'blocking' => false,
		];
		ep_remote_request( '_bulk', $request_args, [], 'index_post' );
	}

	private function get_blog_indices() {
		$query = new \WP_Site_Query();
		$sites = array_map( 'intval', $query->query( [
			'number'       => 0,
			'fields'       => 'ids',
			'site__not_in' => 1,
			'archived'     => false,
			'deleted'      => false,
		] ) );

		return array_map( 'ep_get_index_name', $sites );
	}
}