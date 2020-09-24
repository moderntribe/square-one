<?php

namespace Tribe\Project\Templates\Models;

class Post_List_Object {
	public const POST_ID   = 'post_id';
	public const TITLE     = 'title';
	public const CONTENT   = 'content';
	public const EXCERPT   = 'excerpt';
	public const IMAGE     = 'image';
	public const LINK      = 'link';
	public const POST_TYPE = 'post_type';

	/**
	 * @var int
	 */
	private $post_id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var string
	 */
	private $excerpt;

	/**
	 * @var int
	 */
	private $image_id;

	/**
	 * @var array
	 */
	private $link;

	/**
	 * @var string
	 */
	private $post_type;

	/**
	 * @return int
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_content() {
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function get_excerpt() {
		return $this->excerpt;
	}

	/**
	 * @return int
	 */
	public function get_image_id() {
		return $this->image_id;
	}

	/**
	 * @return array
	 */
	public function get_link() {
		return $this->link;
	}

	/**
	 * @return string
	 */
	public function get_post_type() {
		return $this->post_type;
	}


	public function set_post_id( int $post_id ) {
		$this->post_id = $post_id;

		return $this;
	}


	public function set_title( string $title ) {
		$this->title = $title;

		return $this;
	}


	public function set_content( string $content ) {
		$this->content = $content;

		return $this;
	}


	public function set_excerpt( string $excerpt ) {
		$this->excerpt = $excerpt;

		return $this;
	}


	public function set_image_id( int $id ) {
		$this->image_id = $id;

		return $this;
	}

	public function set_link( array $link ) {
		$this->link = $link;

		return $this;
	}

	public function set_post_type( string $post_type ) {
		$this->post_type = $post_type;

		return $this;
	}

}
