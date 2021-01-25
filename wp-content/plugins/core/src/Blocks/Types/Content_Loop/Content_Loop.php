<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Content_Loop;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class Content_Loop extends Block_Config {
	public const NAME = 'contentloop';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const LEADIN      = 'leadin';
	public const CTA         = 'cta';

	public const QUERY_TYPE        = 'query_type';
	public const QUERY_TYPE_AUTO   = 'query_type_auto';
	public const QUERY_TYPE_MANUAL = 'query_type_manual';

	// Manual query fields
	public const MANUAL_QUERY        = 'manual_query';
	public const MANUAL_POST_MESSAGE = 'manual_post_message';
	public const MANUAL_POST         = 'manual_post';
	public const MANUAL_TOGGLE       = 'manual_toggle';
	public const MANUAL_TITLE        = 'manual_title';
	public const MANUAL_EXCERPT      = 'manual_excerpt';
	public const MANUAL_CTA          = 'manual_cta';
	public const MANUAL_THUMBNAIL    = 'manual_thumbnail';
	public const MANUAL_UPPER_META   = 'manual_upper_meta';
	public const MANUAL_LOWER_META   = 'manual_lower_meta';

	//Query Fields
	public const QUERY_GROUP      = 'query_group';
	public const QUERY_LIMIT      = 'query_limit';
	public const QUERY_TAXONOMIES = 'query_taxonomy_terms';
	public const QUERY_POST_TYPES = 'query_post_types';

	//Layout Fields
	public const LAYOUT           = 'layout';
	public const LAYOUT_ROW       = 'layout_row';
	public const LAYOUT_FEATURE   = 'layout_feature';
	public const LAYOUT_COLUMNS   = 'layout_columns';

	public const ALLOWED_POST_TYPES = [
		Post::NAME,
		Page::NAME,
	];

	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Content Loop', 'tribe' ),
			'description' => __( 'A loop of auto or manual set posts with style options', 'tribe' ),
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>', // TODO: set SVG icon
			'keywords'    => [ __( 'content', 'loop', 'tribe' ) ],
			'category'    => 'common',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
				'html'   => false,
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields() {
		//==========================================
		// Content Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_CONTENT, __( 'Content', 'tribe' ), 'accordion' ) )
			->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => __( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::LEADIN, [
					'label' => __( 'Lead in', 'tribe' ),
					'name'  => self::LEADIN,
					'type'  => 'text',
				] )
			)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label' => __( 'Description', 'tribe' ),
					'name'  => self::DESCRIPTION,
					'type'  => 'wysiwyg',
				] )
			)->add_field( new Field( self::NAME . '_' . self::CTA, [
					'label' => __( 'Call to Action', 'tribe' ),
					'name'  => self::CTA,
					'type'  => 'link',
				] )
			)->add_field( new Field( self::NAME . '_' . self::QUERY_TYPE, [
					'label'   => __( 'Type of Query', 'tribe' ),
					'name'    => self::QUERY_TYPE,
					'type'    => 'button_group',
					'choices' => [
						self::QUERY_TYPE_AUTO   => __( 'Automatic', 'tribe' ),
						self::QUERY_TYPE_MANUAL => __( 'Manual', 'tribe' ),
					],
				] )
			)->add_field(
				$this->get_query_group_fields()
			)->add_field(
				$this->get_manual_group()
			);

		//==========================================
		// Setting Fields
		//==========================================
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, __( 'Settings', 'tribe' ), 'accordion' ) )
		->add_field(
			new Field( self::NAME . '_' . self::LAYOUT, [
				'type'            => 'image_select',
				'name'            => self::LAYOUT,
				'choices'         => [
					self::LAYOUT_ROW       => __( 'Row', 'tribe' ),
					self::LAYOUT_FEATURE   => __( 'Feature', 'tribe' ),
					self::LAYOUT_COLUMNS   => __( 'Columns', 'tribe' ),
				],
				'default_value'   => self::LAYOUT_ROW,
				'multiple'        => 0,
				'image_path'      => sprintf(
					'%sassets/img/admin/blocks/%s/',
					trailingslashit( get_template_directory_uri() ),
					self::NAME
				),
				'image_extension' => 'svg',
			] )
		);
	}

	protected function get_manual_group(): Repeater {
		$repeater = new Repeater( self::NAME . '_' . self::MANUAL_QUERY, [
			'min'               => 3,
			'max'               => 10,
			'layout'            => 'block',
			'name'              => self::MANUAL_QUERY,
			'label'             => __( 'Manual Items', 'tribe' ),
			'conditional_logic' => [
				[
					[
						'field'    => 'field_' . self::NAME . '_' . self::QUERY_TYPE,
						'operator' => '==',
						'value'    => self::QUERY_TYPE_MANUAL,
					],
				],
			],

		] );

		$repeater->add_field(
			new Field( self::MANUAL_POST_MESSAGE, [
				'label' => __( 'Start w/ Existing Content', 'tribe' ),
				'name'  => self::MANUAL_POST_MESSAGE,
			] )
		)->add_field(
			new Field( self::MANUAL_POST, [
				'label'      => __( 'Post Selection', 'tribe' ),
				'name'       => self::MANUAL_POST,
				'type'       => 'post_object',
				'post_type'  => self::ALLOWED_POST_TYPES,
				'allow_null' => 1,
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::MANUAL_TOGGLE, [
				'label'        => __( 'Create or Override Content', 'tribe' ),
				'name'         => self::MANUAL_TOGGLE,
				'type'         => 'accordion',
			] )
		)->add_field(
			new Field( self::MANUAL_UPPER_META, [
				'label'             => __( 'Tag', 'tribe' ),
				'type'              => 'text',
				'name'              => self::MANUAL_UPPER_META,
			] )
		)->add_field(
			new Field( self::MANUAL_TITLE, [
				'label'             => __( 'Title', 'tribe' ),
				'type'              => 'text',
				'name'              => self::MANUAL_TITLE,
			] )
		)->add_field(
			new Field( self::MANUAL_LOWER_META, [
				'label'             => __( 'Date', 'tribe' ),
				'type'              => 'text',
				'name'              => self::MANUAL_LOWER_META,
			] )
		)->add_field(
			new Field( self::MANUAL_EXCERPT, [
				'label'             => __( 'Excerpt', 'tribe' ),
				'type'              => 'textarea',
				'name'              => self::MANUAL_EXCERPT,
			] )
		)->add_field(
			new Field( self::MANUAL_CTA, [
				'name'              => self::MANUAL_CTA,
				'label'             => __( 'Call to Action', 'tribe' ),
				'type'              => 'link',
			] )
		)->add_field(
			new Field( self::MANUAL_THUMBNAIL, [
				'name'              => self::MANUAL_THUMBNAIL,
				'label'             => __( 'Thumbnail Image', 'tribe' ),
				'type'              => 'image',
				'return_format'     => 'id',
			] )
		);

		return $repeater;
	}

	protected function get_query_group_fields(): Field_Group {
		$group = new Field_Group( self::NAME . '_' . self::QUERY_GROUP, [
			'label'             => __( 'Build Your Query', 'tribe' ),
			'name'              => self::QUERY_GROUP,
			'conditional_logic' => [
				[
					[
						'field'    => 'field_' . self::NAME . '_' . self::QUERY_TYPE,
						'operator' => '==',
						'value'    => self::QUERY_TYPE_AUTO,
					],
				],
			],
		] );
		$group->add_field(
			new Field( self::NAME . '_' . self::QUERY_POST_TYPES, [
				'type'     => 'select',
				'label'    => __( 'Post Types', 'tribe' ),
				'multiple' => true,
				'ui'       => true,
				'name'     => self::QUERY_POST_TYPES,
				'choices'  => $this->get_post_types_labels(),
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::QUERY_LIMIT, [
				'label'         => __( 'Limit', 'tribe' ),
				'name'          => self::QUERY_LIMIT,
				'min'           => 3,
				'max'           => 10,
				'step'          => 1,
				'type'          => 'range',
				'default_value' => 3,
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::QUERY_TAXONOMIES, [
				'type'          => 'select',
				'multiple'      => true,
				'label'         => __( 'Filter by Taxonomies', 'tribe' ),
				'ui'            => true,
				'name'          => self::QUERY_TAXONOMIES,
				'return_format' => 'value',
				'choices'       => $this->get_taxonomies_for_post_types(),
			] )
		);

		//We need to loop through all public taxonomies to get taxonomy terms that only relate to the post type.
		foreach ( $this->get_taxonomies_for_post_types() as $name => $label ) {
			$group->add_field( new Field( self::NAME . '_' . self::QUERY_TAXONOMIES . '_' . $name, [
				'label'             => sprintf(
					__( 'Filter by %s Terms', 'tribe' ),
					$label
				),
				'name'              => self::QUERY_TAXONOMIES . '_' . $name,
				'type'              => 'taxonomy',
				'field_type'        => 'multi_select',
				'taxonomy'          => $name,
				'allow_null'        => false,
				'add_term'          => false,
				'save_terms'        => false,
				'load_terms'        => false,
				'return_format'     => 'object',
				'conditional_logic' => [
					[
						[
							'field'    => 'field_' . self::NAME . '_' . self::QUERY_TAXONOMIES,
							'operator' => '==contains',
							'value'    => $name,
						],
					],
				],
			] ) );
		}

		return $group;
	}
	/**
	 * @return array
	 */
	private function get_taxonomies_for_post_types(): array {
		$taxonomies       = get_object_taxonomies( self::ALLOWED_POST_TYPES, 'object' );
		$taxonomy_options = [];
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $taxonomy_options;
	}

	/**
	 * @return array
	 */
	private function get_post_types_labels(): array {
		$array = [];
		foreach ( self::ALLOWED_POST_TYPES as $cpt ) {
			$obj = get_post_type_object( $cpt );
			if ( ! $obj ) {
				continue;
			}
			$array[ $cpt ] = esc_html( $obj->label );
		}

		return $array;
	}
}
