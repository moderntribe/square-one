<?php


namespace Tribe\Project\Theme;



class Oembed_Filter {
	const CACHE_PREFIX = '_oembed_filtered_';
	private $supported_providers = [
		'Vimeo',
		'YouTube',
	];

	public function hook() {
		add_filter( 'oembed_dataparse', [ $this, 'setup_lazyload_html' ], 1000, 3 );
		add_filter( 'embed_oembed_html', [ $this, 'filter_frontend_html_from_cache' ], 1, 4 );
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

		$figure_class = 'wp-embed-lazy';

		if ( $data->provider_name === 'YouTube' ) {
			$embed_id    = $this->get_youtube_embed_id( $url );
			$video_thumb = $this->get_youtube_max_resolution_thumbnail( $url );

			if ( strpos( $video_thumb, 'maxresdefault' ) === false ) {
				$figure_class .= ' wp-embed-lazy--low-res';
			}

		} else {
			$embed_id    = $this->get_vimeo_embed_id( $url );
			$video_thumb = $data->thumbnail_url;
		}

		if ( empty( $video_thumb ) ) {
			return $html; // with no thumbnail, we use the default embed
		}

		$frontend_html = '<figure class="'. esc_attr( $figure_class ) .'" data-js="lazyload-embed" data-embed-provider="'. esc_attr( strtolower( $data->provider_name ) ) .'">';
		$frontend_html .= '<a href="'. esc_url( $url ) .'" class="wp-embed-lazy__trigger" data-js="lazyload-trigger" title="'. esc_attr( $data->title ) .'" data-embed-id="'. esc_attr( $embed_id ) .'">';
		$frontend_html .= '<img class="wp-embed-lazy__image lazyload" src="'. trailingslashit( get_template_directory_uri() ) . 'img/shims/16x9.png' .'" data-src="'. esc_url( $video_thumb ) .'" alt="'. esc_attr( $data->title ) .'" />';
		$frontend_html .= '<figcaption class="wp-embed-lazy__caption">';
		$frontend_html .= '<i class="wp-embed-lazy__icon icon icon-play"></i>';
		$frontend_html .= '<span class="wp-embed-lazy__trigger-label">' . __( 'Play Video', 'tribe' ) . '</span>';
		$frontend_html .= '<span class="wp-embed-lazy__title">'. esc_html( $data->title ) .'</span>';
		$frontend_html .= '</figcaption>';
		$frontend_html .= '</a>';
		$frontend_html .= '</figure>';

		$this->cache_frontend_html( $frontend_html, $url );

		/*
		 * Don't return the updated value here. We want
		 * WordPress to store its default HTML in its cache,
		 * and we'll only overwrite it on the front end.
		 */
		return $html;

	}

	/**
	 * If we've cached replacement HTML for a URL, override
	 * the default with the cached value.
	 */
	public function filter_frontend_html_from_cache( $html, $url, $attr, $post_id ) {
		if ( is_admin() ) {
			return $html;
		}
		$cached = get_option( $this->get_cache_key( $url ), '' );
		return empty( $cached ) ? $html : $cached;
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

	/**
	 * Store the front-end HTML for a URL
	 * in the options table
	 *
	 * WordPress will regenerate the oembed cache
	 * whenever its cache expires. When it does so,
	 * this filter will run again and update at the
	 * same time.
	 *
	 * @param string $frontend_html
	 * @param string $url
	 */
	private function cache_frontend_html( $frontend_html, $url ) {
		update_option( $this->get_cache_key( $url ), $frontend_html );
	}

	/**
	 * @param string $url
	 * @return string The option name to use to store the cache for a URL
	 */
	private function get_cache_key( $url ) {
		$hash = md5( $url );
		return static::CACHE_PREFIX . $hash;
	}
}
