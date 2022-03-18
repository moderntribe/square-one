<?php declare(strict_types=1);

/**
 * Holds various filters for P2P.
 *
 * @package Tribe
 */

namespace Tribe\Project\P2P;

/**
 * Class to hold p2p filters.
 */
class P2P_Filters {

	/**
	 * Fix title for P2P connection and candidate.
	 *
	 * @param string|null                   $title Current title for the item.
	 * @param \WP_Post                      $post  Post object for the item.
	 * @param \P2P_Directed_Connection_Type $ctype Type object.
	 *
	 * @return string                              Modified title for the item.
	 */
	public function fix_title( ?string $title, \WP_Post $post, \P2P_Directed_Connection_Type $ctype ): string {
		return ! empty( $title ) ? $title : $post->post_title;
	}

}
