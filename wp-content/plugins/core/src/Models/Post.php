<?php

namespace Tribe\Project\Models;

use Tribe\Project\Components\Component_Factory;
use Tribe\Project\Templates\Components\Image as Image_Component;
use Tribe\Project\Theme\Config\Image_Sizes;
use \WP_Post;

/**
 * Class Post
 *
 * @package Tribe\Project\Models
 */
class Post extends Model {

	protected $wp_post;

	protected $ID;
	protected $post_author;
	protected $post_date;
	protected $post_date_gmt;
	protected $post_content;
	protected $post_title;
	protected $post_excerpt;
	protected $post_status;
	protected $comment_status;
	protected $ping_status;
	protected $post_password;
	protected $post_name;
	protected $to_ping;
	protected $pinged;
	protected $post_modified;
	protected $post_modified_gmt;
	protected $post_content_filtered;
	protected $post_parent;
	protected $guid;
	protected $menu_order;
	protected $post_type;
	protected $post_mime_type;
	protected $comment_count;

	protected $time_formats = [
		'c',
		'F j, Y',
	];

	/**
	 * Post constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id = null ) {
		$this->wp_post = $this->get_post_from_id( $id );
		$this->import();
	}

	/**
	 * @param $post
	 *
	 * @return bool|false|mixed|WP_Post|null
	 */
	protected function get_post_from_id( $post ) {
		if ( empty( $post ) && isset( $GLOBALS['post'] ) ) {
			$post = $GLOBALS['post'];
		}

		if ( $post instanceof WP_Post ) {
			return $post;
		} elseif ( is_object( $post ) ) {
			if ( empty( $post->filter ) ) {
				$_post = sanitize_post( $post, 'raw' );
				$_post = new WP_Post( $_post );
			} elseif ( 'raw' == $post->filter ) {
				$_post = new WP_Post( $post );
			} else {
				$_post = WP_Post::get_instance( $post->ID );
			}
		} else {
			$_post = WP_Post::get_instance( $post );
		}

		if ( ! $_post ) {
			return null;
		}

		return $_post;
	}

	/**
	 * Import data to protected properties from Post object.
	 */
	protected function import() {
		if ( is_null( $this->wp_post ) ) {
			return;
		}
		$data = $this->wp_post->to_array();
		foreach ( $data as $key => $value ) {
			$this->$key = $value;
		}
	}

	/**
	 * @return mixed
	 */
	public function title() {
		return apply_filters( 'the_title', get_the_title( $this->ID ) );
	}

	/**
	 * @param array $args
	 *
	 * @return array|\WP_Error
	 */
	public function categories( $args = [] ) {
		return wp_get_post_categories( $this->ID, $args );
	}

	/**
	 * @return mixed
	 */
	public function content() {
		return apply_filters( 'the_content', $this->post_content );
	}

	/**
	 * @return mixed
	 */
	public function excerpt() {
		return apply_filters( 'the_excerpt', get_the_excerpt( $this->ID ) );
	}

	/**
	 * @return bool|false|string|\WP_Error
	 */
	public function permalink() {
		return get_the_permalink( $this->ID );
	}

	/**
	 * @return string
	 */
	public function image() {
		/**
		 * @var Component_Factory $factory
		 */
		$factory = tribe_project()->container()->get( Component_Factory::class );
		$image_id = get_post_thumbnail_id( $this->ID );

		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = new Image( $image_id );
		} catch ( \Exception $e ) {
			return '';
		}

		$options = [
			Image_Component::ATTACHMENT      => $image,
			Image_Component::AS_BG           => false,
			Image_Component::WRAPPER_CLASSES => [ 'item__image' ],
			Image_Component::SHIM            => trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/shims/16x9.png',
			Image_Component::SRC_SIZE        => Image_Sizes::CORE_FULL,
			Image_Component::SRCSET_SIZES    => [
				Image_Sizes::CORE_FULL,
				Image_Sizes::CORE_MOBILE,
				Image_Sizes::SOCIAL_SHARE,
			],
		];

		return $factory->get( Image_Component::class, $options )->get_rendered_output();
	}

	/**
	 * @return array
	 */
	public function time() {
		$times = [];
		foreach ( $this->time_formats as $format ) {
			$times[ $format ] = get_the_time( $format );
		}

		return $times;
	}

	/**
	 * @return array
	 */
	public function author() {
		return [
			'name' => get_the_author(),
			'url'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		];
	}
}
