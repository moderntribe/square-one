<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Libs\Field_Models\Field_Model;
use Tribe\Libs\Field_Models\Models\Cta;
use Tribe\Libs\Field_Models\Models\Image;
use WP_Post;

/**
 * Proxy object to add additional data on top of WP_Post and proxy
 * property requests to the underlying WP_Post object.
 *
 * @mixin \WP_Post
 */
class Post_Proxy extends Field_Model {

	public Cta $cta;
	public Image $image;
	private WP_Post $post;

	public function __construct( array $parameters = [] ) {
		$this->post = new WP_Post( (object) $parameters );

		parent::__construct( $parameters );
	}

	/**
	 * Return the delegated WP_Post object.
	 *
	 * @return \WP_Post
	 */
	public function post(): WP_Post {
		return $this->post;
	}

	/**
	 * Proxy properties to the WP_Post delegate.
	 *
	 * @param string $property
	 *
	 * @return array|mixed|null
	 */
	public function __get( string $property ) {
		return $this->post->{$property};
	}

}
