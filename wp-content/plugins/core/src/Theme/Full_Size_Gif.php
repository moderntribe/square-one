<?php

namespace Tribe\Project\Theme;

class Full_Size_Gif {

	/**
	 * If inserting a gif always place the full size image
	 *
	 * @param string $html
	 * @param int $send_id
	 * @param array $attachment
	 *
	 * @return string
	 */
	public function full_size_only_gif_html( $html, $send_id, $attachment ) {
		$meta = wp_get_attachment_metadata( $send_id );
		if ( ! isset( $meta['file'], $meta['width'], $meta['height'] ) ) {
			return $html;
		}

		if ( ! $this->is_gif( $meta['file'] ) ) {
			return $html;
		}

		$new_html = '';
		$xml = simplexml_load_string( $html );
		foreach( $xml->attributes() as $a => $b ) {
			switch ( $a ) {
				case 'src' :
					$new_html .= ' src="' . wp_get_attachment_image_url( $send_id, 'full' ) . '"';
					break;
				case 'width' :
					$new_html .= " width=\"{$meta['width']}\"";
					break;
				case 'height' :
					$new_html .= " height=\"{$meta['height']}\"";
					break;
				default :
					$new_html .= " $a=\"$b\"";
			}
		}
		$new_html = "<img $new_html />";
		return $new_html;
	}

	/**
	 * @param $response
	 * @param $post
	 * @param $request
	 *
	 * @return mixed
	 */
	public function full_size_only_gif_rest( $response, $post, $request ) {
		if ( ! isset( $response->data['media_details']['sizes'] ) ) {
			return $response;
		}
		if ( $response->data['mime_type'] !== 'image/gif' ) {
			return $response;
		}
		$sizes = [];
		foreach ( $response->data['media_details']['sizes'] as $size => $details ) {
			$sizes[ $size ] = $details;
			$sizes[ $size ]['source_url'] = $response->data['media_details']['sizes']['full']['source_url'];
		}
		$response->data['media_details']['sizes'] = $sizes;
		return $response;
	}

	/**
	 * @param $src
	 * @param $attachment_id
	 *
	 * @return mixed
	 */
	public function full_size_only_gif_src( $src, $attachment_id ) {
		$meta = wp_get_attachment_metadata( $attachment_id );
		if ( ! isset( $meta['file'], $meta['width'], $meta['height'] ) ) {
			return $src;
		}
		if ( ! $this->is_gif( $meta['file'] ) ) {
			return $src;
		}
		$src[0] = wp_get_attachment_image_url( $attachment_id, 'full' );

		return $src;
	}

	/**
	 * @param $src
	 *
	 * @return mixed
	 */
	public function is_gif( $src ) {
		$parts = explode( '.', $src );
		$ext   = strtolower( $parts[ \count( $parts ) - 1 ] );

		return $ext === 'gif';
	}

}
