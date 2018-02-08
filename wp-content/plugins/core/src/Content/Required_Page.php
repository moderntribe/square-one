<?php


namespace Tribe\Project\Content;


use Tribe\Libs\ACF;
use Tribe\Project\Post_Types\Page\Page;

/**
 * Class Required_Page
 *
 * @abstract
 *
 * Automatically creates a page with the given config, and registers
 * a setting to reference the page.
 */
abstract class Required_Page {
	const NAME = '';

	private $field_group_key;

	public function __construct( $field_group_key = '' ) {
		if ( ! static::NAME ) {
			throw new \LogicException( __( 'NAME constant must be set', 'tribe' ) );
		}
		$this->field_group_key = $field_group_key;
	}

	/**
	 * @return string The post type of the post that will be created
	 */
	protected function get_post_type() {
		return Page::NAME;
	}

	/**
	 * @return string The title of the post
	 */
	abstract protected function get_title();

	/**
	 * @return string The slug of the post
	 */
	abstract protected function get_slug();

	/**
	 * @return string The content of the post
	 */
	protected function get_content() {
		return '';
	}

	/**
	 * Ensure that there is a page designated as this page
	 * at all times. Creates one if necessary.
	 *
	 * @return void
	 * @action admin_init
	 */
	public function ensure_page_exists() {
		$post_id = $this->get_post_id();
		if ( ! empty( $post_id ) ) {
			return;
		}
		$post_id = $this->create_post();
		if ( ! empty( $post_id ) ) {
			$this->set_post_id( $post_id );
		}
	}

	/**
	 * @return int The ID of the post registered as this page
	 */
	private function get_post_id() {
		return (int) get_field( static::NAME, 'option', false );
	}

	/**
	 * Set the ID of the post registered as this page
	 *
	 * @param int $post_id
	 *
	 * @return void
	 */
	private function set_post_id( $post_id ) {
		update_field( static::NAME, (int) $post_id, 'option' );
	}

	/**
	 * @param $post_id
	 *
	 * @return void
	 * @action trashed_post
	 * @action deleted_post
	 */
	public function clear_option_on_delete( $post_id ) {
		$existing = $this->get_post_id();
		if ( $existing === (int) $post_id ) {
			delete_field( static::NAME, 'option' );
		}
	}

	/**
	 * Create the post for this config
	 *
	 * @return int the ID of the created post
	 */
	protected function create_post() {
		$args    = $this->get_post_args();
		$post_id = wp_insert_post( $args );

		return $post_id;
	}

	/**
	 * @return array The args for creating the post
	 */
	protected function get_post_args() {
		return [
			'post_type'      => $this->get_post_type(),
			'post_status'    => 'publish',
			'post_title'     => $this->get_title(),
			'post_name'      => $this->get_slug(),
			'post_content'   => $this->get_content(),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		];
	}

	/**
	 * Adds a field to control the page selection to an existing meta group
	 *
	 * @return void
	 * @action acf/init 20
	 */
	public function register_setting() {
		$config = $this->get_field_config();
		if ( $config ) {
			acf_add_local_field( $config );
		}
	}

	/**
	 * @return array The field configuration array
	 */
	protected function get_field_config() {
		if ( empty( $this->field_group_key ) ) {
			return [];
		}
		$field = new ACF\Field( static::NAME );

		$field->set_attributes( [
			'parent'        => $this->field_group_key,
			'label'         => $this->get_field_label(),
			'name'          => static::NAME,
			'type'          => 'post_object',
			'post_type'     => $this->get_post_type(),
			'return_format' => 'int',
		] );

		return $field->get_attributes();
	}

	protected function get_field_label() {
		return sprintf( __( '%s Page', 'tribe' ), $this->get_title() );
	}

}