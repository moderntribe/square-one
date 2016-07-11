<?php


namespace Tribe\Project\Theme;



class Oembed_Filter {
	private $supported_providers = [
		'Vimeo',
		'YouTube',
	];

	public function hook() {
		if ( ! is_admin() ) {
			add_filter( 'oembed_dataparse', [ $this, 'setup_lazyload_html' ], 10, 3 );
		}
		add_filter( 'embed_oembed_html', [ $this, 'wrap_oembed_shortcode_output' ], 99, 4 );
	}

	/**
	 * Replace oembed HTML with a lazyload container
	 * 
	 * Currently supports YouTube and Vimeo embeds.
	 * 
	 * @param string $html The returned oEmbed HTML.
	 * @param object $data A data object result from an oEmbed provider.
	 * @param string $url  The URL of the content to be embedded.
	 * @return string
	 */
	public function setup_lazyload_html( $html, $data, $url ) {

		if ( ! in_array( $data->provider_name, $this->supported_providers, true ) ) {
			return $html;
		}

		$figure_class = 'wp-embed-lazy ' . strtolower( $data->provider_name );

		if ( $data->provider_name === 'YouTube' ) {
			$embed_id    = $this->get_youtube_embed_id( $url );
			$video_thumb = $this->get_youtube_max_resolution_thumbnail( $url );

			if ( strpos( $video_thumb, 'maxresdefault' ) === false ) {
				$figure_class .= ' low-resolution';
			}

		} else {
			$embed_id    = $this->get_vimeo_embed_id( $url );
			$video_thumb = $data->thumbnail_url;
		}

		if ( empty( $video_thumb ) ) {
			return $html; // with no thumbnail, we use the default embed
		}

		$html = '<figure class="'. esc_attr( $figure_class ) .'">';
		$html .= '<a href="'. esc_url( $url ) .'" class="wp-embed-lazy-launch" title="'. esc_attr( $data->title ) .'" data-embed-id="'. esc_attr( $embed_id ) .'">';
		$html .= '<img class="wp-embed-lazy-thumb lazyload" data-src="'. esc_url( $video_thumb ) .'" alt="'. esc_attr( $data->title ) .'" />';
		$html .= '<figcaption class="wp-embed-lazy-caption">';
		$html .= '<i class="icon icon-play"></i>';
		$html .= '<span class="wp-embed-lazy-prompt">' . __( 'Play Video', 'tribe' ) . '</span>';
		$html .= '<span class="wp-embed-lazy-title">'. esc_html( $data->title ) .'</span>';
		$html .= '</figcaption>';
		$html .= '</a>';
		$html .= '</figure>';

		return $html;

	}

	/**
	 * Add wrapper around embeds to setup CSS for embed aspect ratios
	 */
	public function wrap_oembed_shortcode_output( $html, $url, $attr, $post_id ) {
		return sprintf( '<div class="wp-embed"><div class="wp-embed-wrap">%s</div></div>', $html );
	}


	/**
	 * Get the highest resolution thumbnail we can get for
	 * a YouTube video
	 *
	 * @todo Use \Tribe\Libs\Oembed\YouTube when it's working
	 * 
	 * @param string $url
	 * @return string
	 */
	private function get_youtube_max_resolution_thumbnail( $url ) {

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

	/**
	 * Extract the video ID from a YouTube URL
	 * 
	 * @param string $url
	 * @return string
	 */
	private function get_youtube_embed_id( $url ) {
		preg_match( '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $url, $video_id );

		return ! empty( $video_id[0] ) ? $video_id[0] : '';
	}

	/**
	 * Extract the video ID from a vimeo URL
	 * 
	 * @param string $url
	 * @return string
	 */
	private function get_vimeo_embed_id( $url ) {
		preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $video_id );

		return ! empty( $video_id[5] ) ? $video_id[5] : '';
	}
}
