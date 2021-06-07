<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\Traits;

use Tribe\ACF_Post_List\Post_List_Field;
use WP_Post;

trait Post_List_Field_Formatter {

	/**
	 * Format a post as the Post List Field expects.
	 *
	 * @param \WP_Post $_post
	 * @param int     $excerpt_words How many words the excerpt should be, 0 is no trimming.
	 *
	 * @return array
	 *
	 * @see Post_List_Field::format_post()
	 */
	protected function formatted_post( WP_Post $_post, int $excerpt_words = 0 ): array {
		global $post;
		$post = $_post;
		setup_postdata( $post );
		$post_array = [
			'title'     => get_the_title(),
			'content'   => get_the_content(),
			'excerpt'   => $excerpt_words ? wp_trim_words( get_the_excerpt(), $excerpt_words ) : get_the_excerpt(),
			'image_id'  => get_post_thumbnail_id(),
			'link'      => [
				'url'    => get_the_permalink(),
				'target' => '',
				'title'  => get_the_title(),
			],
			'post_type' => get_post_type(),
			'post_id'   => $_post->ID,
		];

		wp_reset_postdata();

		return $post_array;
	}

}
