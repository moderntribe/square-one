<?php

namespace Tribe\Project\Templates\Models;

class Post_List_Object {
	public const POST_ID   = 'post_id';
	public const POST_DATE = 'post_date';
	public const TITLE     = 'title';
	public const CATEGORY  = 'category';
	public const CONTENT   = 'content';
	public const EXCERPT   = 'excerpt';
	public const IMAGE     = 'image';
	public const LINK      = 'link';
	public const POST_TYPE = 'post_type';

	private int $post_id = 0;
	private string $post_date = '';
	private string $title = '';
	private array $category = [];
	private string $content = '';
	private string $excerpt = '';
	private int $image_id = 0;
	private array $link = [];
	private string $post_type = '';

	/**
	 * @return int
	 */
	public function get_post_id(): int {
		return $this->post_id;
	}

	/**
	 * @return string
	 */
	public function get_post_date(): string {
		return $this->post_date;
	}

	/**
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_category(): array {
		return $this->category;
	}

	/**
	 * @return string
	 */
	public function get_content(): string {
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function get_excerpt(): string {
		return $this->excerpt;
	}

	/**
	 * @return int
	 */
	public function get_image_id(): int {
		return $this->image_id;
	}

	/**
	 * @return array
	 */
	public function get_link(): array {
		return $this->link;
	}

	/**
	 * @return string
	 */
	public function get_post_type(): string {
		return $this->post_type;
	}


	/**
	 * @param int $post_id
	 *
	 * @return Post_List_Object
	 */
	public function set_post_id( int $post_id ) {
		$this->post_id = $post_id;

		return $this;
	}

	/**
	 * @param int $post_date
	 *
	 * @return Post_List_Object
	 */
	public function set_post_date( string $post_date ) {
		$this->post_date = $post_date;

		return $this;
	}

	/**
	 * @param string $title
	 *
	 * @return Post_List_Object
	 */
	public function set_title( string $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * @param array $category
	 *
	 * @return Post_List_Object
	 */
	public function set_category( array $category ) {
		$this->category = $category;

		return $this;
	}

	/**
	 * @param string $content
	 *
	 * @return Post_List_Object
	 */
	public function set_content( string $content ) {
		$this->content = $content;

		return $this;
	}

	/**
	 * @param string $excerpt
	 *
	 * @return Post_List_Object
	 */
	public function set_excerpt( string $excerpt ) {
		$this->excerpt = $excerpt;

		return $this;
	}

	/**
	 * @param int $id
	 *
	 * @return Post_List_Object
	 */
	public function set_image_id( int $id ) {
		$this->image_id = $id;

		return $this;
	}

	/**
	 * @param array $link
	 *
	 * @return Post_List_Object
	 */
	public function set_link( array $link ) {
		$this->link = $link;

		return $this;
	}

	/**
	 * @param string $post_type
	 *
	 * @return Post_List_Object
	 */
	public function set_post_type( string $post_type ) {
		$this->post_type = $post_type;

		return $this;
	}

}
