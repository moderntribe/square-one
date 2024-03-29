<?php declare(strict_types=1);

namespace Tribe\Project\Theme\Media;

use Tribe\Project\Templates\Components\video\Video_Controller;

class Oembed_Filter {

	public const CACHE_PREFIX     = '_oembed_filtered_';
	public const PROVIDER_VIMEO   = 'Vimeo';
	public const PROVIDER_YOUTUBE = 'YouTube';

	/**
	 * @var array|string[]
	 */
	private array $supported_providers;

	public function __construct( array $supported_providers = [ self::PROVIDER_VIMEO, self::PROVIDER_YOUTUBE ] ) {
		$this->supported_providers = $supported_providers;
	}

	/**
	 * Get custom video component markup.
	 *
	 * @param string $html
	 * @param object $data
	 * @param string $url
	 *
	 * @filter oembed_dataparse 999 3
	 *
	 * @return string
	 */
	public function get_video_component( string $html, object $data, string $url ): string {

		// Admin should not get custom markup.
		if ( is_admin() ) {
			return $html;
		}

		// Only generate markup for supported providers.
		if ( ! in_array( $data->provider_name, $this->supported_providers ) ) {
			return $html;
		}

		$classes = [ 'c-video--lazy' ];

		if ( $data->provider_name === self::PROVIDER_YOUTUBE ) {
			$embed_id    = $this->get_youtube_embed_id( $url );
			$video_thumb = $this->get_youtube_max_resolution_thumbnail( $url );

			if ( strpos( $video_thumb, 'maxresdefault' ) === false ) {
				$classes[] = 'c-video--lazy-low-res';
			}
		} else {
			$embed_id    = $this->get_vimeo_embed_id( $url );
			$video_thumb = $data->thumbnail_url ?? '';
		}

		$options       = [
			Video_Controller::THUMBNAIL_URL    => $video_thumb,
			Video_Controller::ATTRS            => $this->get_layout_container_attrs( $data->provider_name, $embed_id, $data->title ),
			Video_Controller::CLASSES          => $classes,
			Video_Controller::VIDEO_URL        => $url,
			Video_Controller::TRIGGER_LABEL    => $data->title,
			Video_Controller::TRIGGER_POSITION => Video_Controller::TRIGGER_POSITION_BOTTOM, // Options: bottom, center
		];
		$frontend_html = tribe_template_part( 'components/video/video', null, $options );
		$this->cache_frontend_html( $frontend_html, $url );

		return $frontend_html;
	}

	/**
	 * If we've cached replacement HTML for a URL, override
	 * the default with the cached value.
	 *
	 * @param string|false $html
	 * @param string $url
	 * @param array $attr
	 *
	 * @filter embed_oembed_html 1
	 *
	 * @return mixed
	 */
	public function filter_frontend_html_from_cache( $html, string $url, array $attr ) {
		if ( is_admin() ) {
			return $html;
		}

		$cached = get_option( $this->get_cache_key( $url ), '' );

		// If cache is empty, try generating new HTML.
		if ( empty( $cached ) ) {
			$this->get_fresh_frontend_html( $html, $url, $attr );
			$cached = get_option( $this->get_cache_key( $url ), '' );
		}

		return empty( $cached ) ? $html : $cached;
	}

	/**
	 * Add wrapper around embeds for admin visual editor styling.
	 *
	 * @param string|false $html
	 *
	 * @filter embed_oembed_html 99
	 *
	 * @return string|false
	 */
	public function wrap_admin_oembed( $html, string $url, array $attr, int $post_id ) {
		if ( ! is_admin() ) {
			return $html;
		}

		return sprintf( '<div class="wp-embed"><div class="wp-embed-wrap">%s</div></div>', $html );
	}

	/**
	 * Get a fresh copy of our custom markup if our cache doesn't exist.
	 *
	 * @param string $html
	 * @param string $url
	 * @param array  $attr
	 *
	 * @return string
	 */
	protected function get_fresh_frontend_html( string $html, string $url, array $attr ): string {

		/**
		 * @var \WP_oEmbed $oembed .
		 */
		$oembed   = _wp_oembed_get_object();
		$provider = $oembed->get_provider( $url, $attr );
		$data     = $oembed->fetch( $provider, $url, $attr );

		return $this->get_video_component( $html, $data, $url );
	}


	private function get_layout_container_attrs( string $provider_name, string $embed_id, string $title ): array {
		return [
			'data-js'             => 'c-video',
			'data-embed-id'       => $embed_id,
			'data-embed-provider' => $provider_name,
			'data-embed-title'    => $title,
		];
	}

	/**
	 * Get the highest resolution thumbnail we can get for
	 * a YouTube video
	 *
	 * @todo Use \Tribe\Libs\Oembed\YouTube when it's working
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function get_youtube_max_resolution_thumbnail( string $url ): string {

		$video_id = $this->get_youtube_embed_id( $url );

		if ( empty( $video_id ) ) {
			return '';
		}

		$maxthumburl = sprintf( 'https://i.ytimg.com/vi/%s/maxresdefault.jpg', $video_id );

		$cache_key = 'yt_thumb_' . md5( $maxthumburl );

		$url = wp_cache_get( $cache_key );

		if ( $url === false ) {
			$url = $maxthumburl;

			$response = wp_remote_head( $maxthumburl );
			if ( wp_remote_retrieve_response_code( $response ) === 404 ) {
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
	 *
	 * @return string
	 */
	private function get_youtube_embed_id( string $url ): string {
		preg_match( '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#', $url, $video_id );

		return ! empty( $video_id[0] ) ? (string) $video_id[0] : '';
	}

	/**
	 * Extract the video ID from a vimeo URL
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function get_vimeo_embed_id( string $url ): string {
		preg_match( '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $video_id );

		return ! empty( $video_id[5] ) ? (string) $video_id[5] : '';
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
	private function cache_frontend_html( string $frontend_html, string $url ): void {
		update_option( $this->get_cache_key( $url ), $frontend_html );
	}

	/**
	 * @param string $url
	 *
	 * @return string The option name to use to store the cache for a URL
	 */
	private function get_cache_key( string $url ): string {
		$hash = md5( $url );

		return static::CACHE_PREFIX . $hash;
	}

}
