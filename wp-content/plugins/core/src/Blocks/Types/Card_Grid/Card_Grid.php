<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Card_Grid;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Sample\Sample;

class Card_Grid extends Block_Config {
	public const NAME = 'cardgrid';

	public const SECTION_CONTENT = 's-content';
	public const TITLE           = 'title';
	public const DESCRIPTION     = 'description';
	public const CTA             = 'cta';

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

	//Query Fields
	public const QUERY_GROUP      = 'query_group';
	public const QUERY_LIMIT      = 'query_limit';
	public const QUERY_TAXONOMIES = 'query_taxonomy_terms';
	public const QUERY_POST_TYPES = 'query_post_types';

	public const SECTION_SETTINGS = 's-settings';
	public const LAYOUT           = 'layout';
	public const LAYOUT_STACKED   = 'stacked';
	public const LAYOUT_INLINE    = 'inline';

	public const ALLOWED_POST_TYPES = [
		Post::NAME,
		Sample::NAME,
	];

	/**
	 * Register the block
	 */
	public function add_block() {
		$this->set_block( new Block( self::NAME, [
			'title'       => __( 'Card Grid', 'tribe' ),
			'description' => __( 'A block of curated posts', 'tribe' ),
			'icon'        => 'sticky',
			'keywords'    => [ __( 'posts', 'tribe' ), __( 'display', 'tribe' ), __( 'text', 'tribe' ) ],
			'category'    => 'layout',
			'supports'    => [
				'align'  => false,
				'anchor' => true,
			],
			'example'     => [
				'attributes' => [
					'mode' => 'preview',
					'data' => [
						self::TITLE       => esc_html__( 'A Selection of Posts', 'tribe' ),
						self::DESCRIPTION => esc_html__(
							'Pellentesque diam diam, aliquet non mauris eu, posuere mollis urna. Nulla eget congue ligula, a aliquam lectus. Duis non diam maximus justo dictum porttitor in in risus.',
							'tribe'
						),
						self::CTA         => [ 'title' => esc_html__( 'Call to Action', 'tribe' ), 'url' => '#' ],
						self::QUERY_TYPE  => self::QUERY_TYPE_AUTO,
						self::QUERY_LIMIT => 3,
					],
				],
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
			 )->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
					'label'        => __( 'Description', 'tribe' ),
					'name'         => self::DESCRIPTION,
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => 0,
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
						 self::LAYOUT_STACKED => __( 'Stacked', 'tribe' ),
						 self::LAYOUT_INLINE  => __( 'Inline', 'tribe' ),
					 ],
					 'default_value'   => self::LAYOUT_STACKED,
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
			'min'               => 2,
			'max'               => 10,
			'layout'            => 'row',
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
				'label'     => __( 'Post Selection', 'tribe' ),
				'name'      => self::MANUAL_POST,
				'type'      => 'post_object',
				'post_type' => self::ALLOWED_POST_TYPES,

			] )
		)->add_field(
			new Field( self::NAME . '_' . self::MANUAL_TOGGLE, [
				'label'        => __( 'Create or Override Content', 'tribe' ),
				'instructions' => __(
					'Data entered below will overwrite the respective data from the post selected above.',
					'tribe'
				),
				'name'         => self::MANUAL_TOGGLE,
				'type'         => 'true_false',
			] )
		)->add_field(
			new Field( self::MANUAL_TITLE, [
				'label'             => __( 'Title', 'tribe' ),
				'type'              => 'text',
				'name'              => self::MANUAL_TITLE,
				'conditional_logic' => [
					[
						'field'    => 'field_' . self::NAME . '_' . self::MANUAL_TOGGLE,
						'operator' => '==',
						'value'    => '1',
					],
				],
			] )
		)->add_field(
			new Field( self::MANUAL_EXCERPT, [
				'label'             => __( 'Excerpt', 'tribe' ),
				'type'              => 'textarea',
				'name'              => self::MANUAL_EXCERPT,
				'conditional_logic' => [
					[
						'field'    => 'field_' . self::NAME . '_' . self::MANUAL_TOGGLE,
						'operator' => '==',
						'value'    => '1',
					],
				],
			] )
		)->add_field(
			new Field( self::MANUAL_CTA, [
				'name'              => self::MANUAL_CTA,
				'label'             => __( 'Call to Action', 'tribe' ),
				'type'              => 'link',
				'conditional_logic' => [
					[
						'field'    => 'field_' . self::NAME . '_' . self::MANUAL_TOGGLE,
						'operator' => '==',
						'value'    => '1',
					],
				],
			] )
		)->add_field(
			new Field( self::MANUAL_THUMBNAIL, [
				'name'              => self::MANUAL_THUMBNAIL,
				'label'             => __( 'Thumbnail Image', 'tribe' ),
				'type'              => 'image',
				'return_format'     => 'id',
				'conditional_logic' => [
					[
						'field'    => 'field_' . self::NAME . '_' . self::MANUAL_TOGGLE,
						'operator' => '==',
						'value'    => '1',
					],
				],
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
				'min'           => 2,
				'max'           => 10,
				'step'          => 1,
				'type'          => 'range',
				'default_value' => 2,
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
