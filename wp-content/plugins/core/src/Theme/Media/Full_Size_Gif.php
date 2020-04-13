<?php

namespace Tribe\Project\Theme\Media;

class Full_Size_Gif {

	/**
	 * @param $data
	 * @param $attachment_id
	 * @param $size
	 *
	 * @return array|false
	 */
	public function full_size_only_gif( $data, $attachment_id, $size ) {
		$meta = wp_get_attachment_metadata( $attachment_id );
		if ( $size !== 'full' && isset( $meta['file'] ) && $this->is_gif( $meta['file'] ) ) {
			return image_downsize( $attachment_id, 'full' );
		}

		return $data;
	}

	/**
	 * @param $src
	 *
	 * @return bool
	 */
	public function is_gif( $src ) {
		return ( pathinfo( $src, PATHINFO_EXTENSION ) === 'gif' );
	}

}
