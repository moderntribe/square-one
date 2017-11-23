<?php


namespace Tribe\Project\Permissions\Object_Meta;


use Tribe\Libs\Object_Meta\Meta_Group;
use Tribe\Project\Permissions\Taxonomies\Section\Section;

class Default_Section extends Meta_Group {
	const NAME = 'default_section';

	const TERM_ID = 'default_' . Section::NAME;

	public function get_keys() {
		return [
			static::TERM_ID,
		];
	}

	public function get_value( $object_id, $key ) {
		if ( in_array( $key, $this->get_keys() ) ) {
			return get_blog_option( $object_id, $key );
		}

		return null;
	}


	/**
	 * @return void
	 *
	 * @action admin_init
	 */
	public function register_group() {
		add_settings_field(
			self::TERM_ID,
			__( 'Default Section', 'tribe' ),
			[ $this, 'render_default_section_field' ],
			$this->get_options_page_slug(),
			'default'
		);
		register_setting( $this->get_options_page_slug(), self::TERM_ID );
	}

	public function render_default_section_field() {
		wp_dropdown_categories( [
			'hide_empty'   => 0,
			'name'         => self::TERM_ID,
			'orderby'      => 'name',
			'selected'     => get_option( self::TERM_ID ),
			'hierarchical' => false,
			'taxonomy'     => Section::NAME,
		] );
	}

	private function get_options_page_slug(): string {
		if ( empty( $this->object_types[ 'settings_pages' ] ) ) {
			return '';
		}

		return reset( $this->object_types[ 'settings_pages' ] );
	}

	/**
	 * If the default term is not set, provide a fallback value.
	 * Start with the default slug set on the Section class.
	 * If that term doesn't exist, fall back to the first
	 * term alphabetically.
	 *
	 * @param int $default_value
	 *
	 * @return int
	 *
	 * @filter default_option_ . self::TERM_ID
	 */
	public function set_default_default_term_id( $default_value ) {
		if ( false !== $default_value ) {
			return $default_value;
		}

		return $this->find_default_term_id();
	}

	private function find_default_term_id() {
		$term = get_term_by( 'slug', Section::DEFAULT, Section::NAME );
		if ( $term ) {
			return (int) $term->term_id;
		}
		$terms = get_terms( [
			'taxonomy'   => Section::NAME,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'number'     => 1,
		] );
		if ( empty( $terms ) ) {
			return 0;
		}

		return (int) reset( $terms )->term_id;
	}

	/**
	 * @param $term_id
	 *
	 * @return void
	 *
	 * @action delete_ . Section::NAME
	 */
	public function unset_option_when_term_deleted( $term_id ) {
		if ( $term_id == get_option( self::TERM_ID ) ) {
			delete_option( self::TERM_ID );
		}
	}

}