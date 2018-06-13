<?php
namespace Tribe\Project\Oembed;

use Tribe\Project\Cache\Cache;

/**
 * Youtube API
 *
 * Creates an object from a Youtube video url.
 * If an api key is not provided this will fall back to oembed with works but
 * does not contain things like high resolution thumbnails or descriptions.
 *
 * An api key may be obtained by registering a project with Google APIs and giving it YouTube access.
 *
 * # Go to the developer console https://console.developers.google.com/project
 * # Click "Create Project" if some already exist otherwise, use the "Select a Project" drop-down and click "Create a
 * Project"
 * # Enter a project name like "Steelcase.com"
 * # Click "Create"
 * # Under the list of "Popular APIs" click "YouTube Data API"
 * # Click "Enable"
 * # Click "Go to Credentials"
 * # Under "Where will you be calling the API from?" select "Web Browser"
 * # Under "What data will you be accessing?" check "Public data"
 * # Click "What credentials do I need?"
 * # Fill out the "Name" field
 * # Leave the "Accept requests from these HTTP referrers (web sites)" blank
 * # Click "Create API key"
 * # Under "Get your credentials" copy the API key and post it here
 *
 * E.G AIzaSyCwuMNgkjhfDWZc_FDrcq8TexW3OMT3I1Q
 *
 *
 * @author  Mat Lipe
 *
 *
 * @link    https://developers.google.com/youtube/v3/getting-started#Sample_Partial_Requests
 *
 */
class YouTube implements \JsonSerializable {
	const API_URL = "https://www.googleapis.com/youtube/v3/videos?id=%id%&key=%api_key%&part=snippet";

	const OEMBED_URL = "http://www.youtube.com/oembed?url=%url%&maxwidth=%width%&maxheight=%height%";

	private $api_key = false;

	private $url;

	/**
	 * cache
	 *
	 * @var \Tribe\Libs\Cache\Cache
	 */
	private $cache;

	public $height = 400;

	public $width = 700;


	public function __construct( $url, $api_key ){
		$this->url     = $url;
		$this->api_key = $api_key;

		$this->cache = new Cache();
	}


	public function set_width( $width ){
		$this->width = $width;
	}


	public function set_height( $height ){
		$this->height = $height;
	}


	/**
	 * Calls the methods to match the structure of a oembed object
	 *
	 * So $video->thumbnail_url becomes $this->get_thumbnail_url()
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	public function __get( $field ){
		if( method_exists( $this, "get_$field" ) ){
			return $this->{"get_$field"}();
		}

		return false;
	}


	public function get_id(){
		$object = $this->get_object();

		return isset( $object->id ) ? $object->id : "";

	}


	public function get_title(){
		$object = $this->get_object();

		return isset( $object->title ) ? $object->title : "";

	}


	public function get_description(){
		$object = $this->get_object();

		return isset( $object->description ) ? $object->description : "";

	}


	public function get_thumbnail_url(){
		$object    = $this->get_object();
		$thumbnail = '';
		if( !empty( $object->thumbnails ) ){
			if( isset( $object->thumbnails->maxres ) ){
				$thumbnail = $object->thumbnails->maxres->url;
			} elseif( isset( $object->thumbnails->high ) ) {
				$thumbnail = $object->thumbnails->high->url;
			} elseif( isset( $object->thumbnails->medium ) ) {
				$thumbnail = $object->thumbnails->medium->url;
			}
			//fallback to oembed url
		} elseif( !empty( $object->thumbnail_url ) ){
			$thumbnail = $object->thumbnail_url;
		}

		return $thumbnail;
	}


	public function get_html(){
		$object = $this->get_object();
		$frame  = "";
		if( !empty( $object->id ) ){
			$frame = '<iframe 
						width="' . $this->width . '" 
						height="' . $this->height . '"
						src="https://www.youtube.com/embed/' . $object->id . '">
				</iframe>';
		}

		return $frame;
	}


	public function get_object(){
		if( isset( $this->object ) ){
			return $this->object;
		}
		if( empty( $this->api_key ) ){
			$this->object = $this->get_oembed();
		} else {
			$this->object = $this->request_from_api();
		}

		return $this->object;
	}


	/**
	 * Get the video object from the api
	 * Contains description and image size which the
	 * oembed does not.
	 * Does not include and html player
	 *
	 * @notice If you don't have an api key use $this->get_oembed()
	 *
	 * @return mixed
	 */
	private function request_from_api(){
		if( empty( $this->api_key ) ){
			return false;
		}

		$cache_key = array(
			__CLASS__,
			__METHOD__,
			'url' => $this->url,
		);

		$object = $this->cache->get( $cache_key );
		if( $object === false ){
			parse_str( parse_url( $this->url, PHP_URL_QUERY ), $_args );
			if( !empty( $_args[ 'v' ] ) ){
				$url = str_replace( '%id%', $_args[ 'v' ], self::API_URL );
				$url = str_replace( '%api_key%', $this->api_key, $url );

				$response = wp_remote_get( $url );
				$object   = json_decode( wp_remote_retrieve_body( $response ) );
				if( !empty( $object ) ){
					$video      = array_shift( $object->items );
					$object     = $video->snippet;
					$object->id = $video->id;
				}
				$this->cache->set( $cache_key, $object, 'tribe', DAY_IN_SECONDS * 2 );
			}
		}

		return $object;
	}


	/**
	 * Get the Oembed object for this video
	 * Does not include things like description and thumbnail size
	 * but does include an html player
	 * Also, does not require an api key
	 *
	 * @notice if you have an api key this method is pretty much redundant
	 *
	 * @return object
	 */
	public function get_oembed(){
		$cache_key = array(
			__CLASS__,
			__METHOD__,
			'url' => $this->url,
		);

		$object = $this->cache->get( $cache_key );
		if( $object === false ){
			$url = str_replace( '%url%', urlencode( $this->url ), self::OEMBED_URL );
			$url = str_replace( '%height%', $this->height, $url );
			$url = str_replace( '%width%', $this->width, $url );

			$response = wp_remote_get( $url );
			$object   = json_decode( wp_remote_retrieve_body( $response ) );
			$this->cache->set( $cache_key, $object, 'tribe', DAY_IN_SECONDS * 2 );
		}

		return $object;
	}


	public function jsonSerialize(){
		return array(
			'id'            => $this->get_id(),
			'url'           => $this->url,
			'title'         => $this->get_title(),
			'video'         => $this->get_html(),
			'thumbnail_url' => $this->get_thumbnail_url(),
			'description'   => $this->get_description(),
			'html'          => $this->get_html(),
		);
	}
}