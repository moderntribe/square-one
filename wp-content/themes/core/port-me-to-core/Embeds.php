<?php

/**
 * Embed Lazyloading
 * Jonathan note that this will need to be combined with whatever work
 * Mat & Samuel have done regarding embeds, namely getting higher res
 * images for YouTube & Vimeo
 */

class Embeds {

	public function __construct() {

		add_filter( 'oembed_dataparse', [ $this, 'customize_oembed_output' ], 10, 3 );

	}

	public function customize_oembed_output( $html, $data, $url ) {

		if ( ! in_array( $data->provider_name, [ 'YouTube', 'Vimeo' ], true ) ) {
			return $html;
		}

		$figure_class = 'wp-embed-lazy ' . strtolower( $data->provider_name );
		$width        = $data->thumbnail_width;
		$height       = $data->thumbnail_height;

		if ( $data->provider_name === 'YouTube' ) {
			$embed_id    = $this->get_youtube_embed_id( $url );
			$video_thumb = $this->get_youtube_max_resolution_thumbnail( $url );

			if ( strpos( $video_thumb, 'maxresdefault' ) !== false ) {
				$width  = '1280';
				$height = '720';
			} else {
				$figure_class .= ' low-resolution';
			}

		} else {
			$embed_id    = $this->get_vimeo_embed_id( $url );
			$video_thumb = $data->thumbnail_url;
		}

		if ( empty( $video_thumb ) ) {
			return $html;
		}

		$html = '<figure class="'. $figure_class .'">';
		$html .= '<a href="'. $url .'" class="wp-embed-lazy-launch" title="'. $data->title .'" data-embed-id="'. $embed_id .'">';
		$html .= '<img class="wp-embed-lazy-thumb lazyload" data-src="'. $video_thumb .'" alt="'. esc_attr( $data->title ) .'" />';
		$html .= '<figcaption class="wp-embed-lazy-caption">';
		$html .= '<i class="icon icon-play"></i>';
		$html .= '<span class="wp-embed-lazy-prompt">Play Video</span>';
		$html .= '<span class="wp-embed-lazy-title">'. $data->title .'</span>';
		$html .= '</figcaption>';
		$html .= '</a>';
		$html .= '</figure>';

		return $html;

	}

	public function get_youtube_max_resolution_thumbnail( $url ) {

		$video_id = $this->get_youtube_embed_id( $url );

		if ( $video_id === null ) {
			return '';
		}

		$maxthumburl = sprintf( 'https://i.ytimg.com/vi/%s/maxresdefault.jpg', $video_id );

		$cache_key = 'yt_thumb_' . md5( $maxthumburl );

		$url = wp_cache_get( $cache_key );

		if ( $url === false ) {
			$url = $maxthumburl;

			$headers = get_headers( $maxthumburl );
			if ( substr( $headers[0], 9, 3 ) === '404' ) {
				$url = 'https://i.ytimg.com/vi/' . $video_id . '/0.jpg';
			}

			wp_cache_set( $cache_key, $url );
		}

		return $url;
	}

	public function get_youtube_embed_id( $url ) {
		preg_match( '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $url, $video_id );

		return ! empty( $video_id[0] ) ? $video_id[0] : null;
	}

	public function get_vimeo_embed_id( $url ) {
		preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $video_id );

		return ! empty( $video_id[5] ) ? $video_id[5] : null;
	}

}

new Embeds();
