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

	private int $post_id;
	private string $title;
	private string $content;
	private string $excerpt;
	private int $image_id;
	private array $link;
	private string $post_type;

	public function __construct( array $args = [] ) {
		$this->post_id   = (int) $args[ self::POST_ID ] ?? 0;
		$this->title     = (string) $args[ self::TITLE ] ?? '';
		$this->content   = (string) $args[ self::CONTENT ] ?? '';
		$this->excerpt   = (string) $args[ self::EXCERPT ] ?? '';
		$this->image_id  = (int) $args[ self::IMAGE ] ?? 0;
		$this->link      = (array) $args[ self::LINK ] ?? [];
		$this->post_type = (string) $args[ self::POST_TYPE ] ?? '';
	}

	/**
	 * @return int
	 */
	public function get_post_id(): int {
		return $this->post_id;
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


}