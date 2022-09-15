<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Contracts\Has_Sub_Fields;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Repeater;
use Tribe\Libs\ACF\Traits\With_Field_Finder;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;
use Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config;
use Tribe\Project\Taxonomies\Category\Category;

/**
 * Inject a field section that allows users to query for different posts or manually
 * curate posts on the fly.
 */
class Post_Loop_Field_Middleware extends Abstract_Field_Middleware implements Cta_Field {

	use With_Cta_Field;
	use With_Field_Finder;

	public const NAME           = 'post_loop';
	public const MIDDLEWARE_KEY = 'post_loop_field_configs';

	public const QUERY_TYPE        = 'query_type';
	public const QUERY_TYPE_AUTO   = 'query_type_auto';
	public const QUERY_TYPE_MANUAL = 'query_type_manual';
	public const QUERY_LIMIT       = 'limit';
	public const QUERY_POST_TYPES  = 'post_types';
	public const QUERY_ORDER       = 'order';
	public const QUERY_ORDER_BY    = 'order_by';

	public const GROUP_QUERY      = 'query';
	public const GROUP_TAXONOMIES = 'taxonomies';

	public const MANUAL_POSTS = 'manual_posts';

	public const MANUAL_POST   = 'post';
	public const MANUAL_TOGGLE = 'manual_toggle';
	public const MANUAL_IMAGE  = 'manual_image';

	// Override exist post fields, their value should match the property in WP_Post
	public const MANUAL_TITLE         = 'post_title';
	public const MANUAL_EXCERPT       = 'post_excerpt';
	public const MANUAL_POST_DATE     = 'post_date';
	public const MANUAL_POST_AUTHOR   = 'post_author';
	public const MANUAL_POST_CATEGORY = 'post_category';

	public const OPTION_ASC  = 'asc';
	public const OPTION_DESC = 'desc';

	// Order by options
	// @link https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
	public const OPTION_AUTHOR        = 'author';
	public const OPTION_COMMENT_COUNT = 'comment_count';
	public const OPTION_DATE          = 'date';
	public const OPTION_ID            = 'ID';
	public const OPTION_MENU_ORDER    = 'menu_order';
	public const OPTION_MODIFIED      = 'modified';
	public const OPTION_NAME          = 'name';
	public const OPTION_NONE          = 'none';
	public const OPTION_PARENT        = 'parent';
	public const OPTION_TITLE         = 'title';
	public const OPTION_TYPE          = 'type';

	protected Post_Loop_Field_Config $config;

	/**
	 * Inject the post loop field group underneath specified parent fields.
	 *
	 * @param \Tribe\Libs\ACF\Block_Config                                                                                       $block
	 * @param array{post_loop_field_configs: \Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config[]}|mixed[] $params
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	public function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		$post_loop_configs = $params[ self::MIDDLEWARE_KEY ] ?? [];

		if ( ! $post_loop_configs || ! is_array( $post_loop_configs ) ) {
			return $block;
		}

		$fields = $block->get_fields();

		// Configure each post loop this block has specified to configure
		foreach ( $post_loop_configs as $post_loop_config ) {
			if ( ! $post_loop_config instanceof Post_Loop_Field_Config ) {
				continue;
			}

			$this->config             = $post_loop_config;
			$this->config->block_name = $block::NAME;

			// Find the field/group/section where our post loop fields will be added to.
			$section = $this->find_field( $fields, $this->config->group );

			if ( ! $section ) {
				continue;
			}

			$section->add_field( $this->get_post_loop_group() );
		}

		return $block;
	}

	protected function get_post_loop_group(): Field_Group {
		$group = new Field_Group( $this->config->block_name . '_' . $this->config->field_name, [
			'name' => $this->config->field_name,
		] );

		foreach ( $this->build_fields() as $field ) {
			$group->add_field( $field );
		}

		return $group;
	}

	/**
	 * @return \Tribe\Libs\ACF\Field[]|\Tribe\Libs\ACF\Contracts\Has_Sub_Fields[]
	 */
	protected function build_fields(): array {
		$fields = [];

		$fields[] = $this->get_query_type_field();
		$fields[] = $this->get_query_group();
		$fields[] = $this->get_taxonomy_filter_group();
		$fields[] = $this->get_manual_query_repeater();

		return $fields;
	}

	protected function get_query_type_choices(): array {
		$choices = [
			self::QUERY_TYPE_AUTO   => esc_html__( 'Automatic', 'tribe' ),
			self::QUERY_TYPE_MANUAL => esc_html__( 'Manual', 'tribe' ),
		];

		if ( $this->config->available_types === Post_Loop_Field_Config::OPTION_MANUAL ) {
			unset( $choices[ self::QUERY_TYPE_AUTO ] );
		}

		if ( $this->config->available_types === Post_Loop_Field_Config::OPTION_QUERY ) {
			unset( $choices[ self::QUERY_TYPE_MANUAL ] );
		}

		return $choices;
	}

	/**
	 * Query Type.
	 *
	 * @return \Tribe\Libs\ACF\Field
	 */
	protected function get_query_type_field(): Field {
		return new Field( $this->build_field_key( self::QUERY_TYPE ), [
			'label'   => esc_html__( 'Query type', 'tribe' ),
			'name'    => self::QUERY_TYPE,
			'type'    => 'button_group',
			'choices' => $this->get_query_type_choices(),
		] );
	}

	/**
	 * Dynamic query acf field group.
	 */
	protected function get_query_group(): Has_Sub_Fields {
		$group = new Field_Group( $this->build_field_key( self::GROUP_QUERY ), [
			'label'             => esc_html__( 'Build Your Query', 'tribe' ),
			'name'              => self::GROUP_QUERY,
			'conditional_logic' => [
				[
					[
						'field'    => $this->get_key_with_prefix( $this->build_field_key( self::QUERY_TYPE ) ),
						'operator' => '==',
						'value'    => self::QUERY_TYPE_AUTO,
					],
				],
			],
		] );

		$fields = [];

		$fields[] = new Field( $this->build_field_key( self::QUERY_LIMIT ), [
			'label'         => esc_html__( 'Limit', 'tribe' ),
			'name'          => self::QUERY_LIMIT,
			'min'           => $this->config->limit_min,
			'max'           => $this->config->limit_max,
			'step'          => 1,
			'type'          => 'range',
			'default_value' => $this->config->query_limit,
		] );

		$fields[] = new Field( $this->build_field_key( self::QUERY_POST_TYPES ), [
			'label'    => esc_html__( 'Post Types', 'tribe' ),
			'name'     => self::QUERY_POST_TYPES,
			'type'     => 'select',
			'choices'  => acf_get_pretty_post_types( $this->config->post_types ),
			'ui'       => true,
			'multiple' => true,
		] );

		$fields[] = new Field( $this->build_field_key( self::QUERY_ORDER ), [
			'label'         => esc_html__( 'Order', 'tribe' ),
			'name'          => self::QUERY_ORDER,
			'type'          => 'select',
			'choices'       => [
				self::OPTION_DESC => esc_html__( 'Descending (newest)', 'tribe' ),
				self::OPTION_ASC  => esc_html__( 'Ascending', 'tribe' ),
			],
			'default_value' => self::OPTION_DESC,
			'multiple'      => false,
			'ui'            => false,
			'ajax'          => false,
			'return_format' => 'value',
		] );

		$fields[] = new Field( $this->build_field_key( self::QUERY_ORDER_BY ), [
			'label'         => esc_html__( 'Order By', 'tribe' ),
			'name'          => self::QUERY_ORDER_BY,
			'type'          => 'select',
			'choices'       => [
				self::OPTION_DATE          => esc_html__( 'Post Date', 'tribe' ),
				self::OPTION_TITLE         => esc_html__( 'Post Title', 'tribe' ),
				self::OPTION_ID            => esc_html__( 'Post ID', 'tribe' ),
				self::OPTION_TYPE          => esc_html__( 'Post Type', 'tribe' ),
				self::OPTION_NAME          => esc_html__( 'Post Slug', 'tribe' ),
				self::OPTION_AUTHOR        => esc_html__( 'Author', 'tribe' ),
				self::OPTION_COMMENT_COUNT => esc_html__( 'Comment Count', 'tribe' ),
				self::OPTION_MENU_ORDER    => esc_html__( 'Menu Order', 'tribe' ),
				self::OPTION_MODIFIED      => esc_html__( 'Last Modified Date', 'tribe' ),
				self::OPTION_NONE          => esc_html__( 'None', 'tribe' ),
				self::OPTION_PARENT        => esc_html__( 'Parent Post/Page ID', 'tribe' ),
			],
			'default_value' => self::OPTION_DATE,
			'multiple'      => false,
			'ui'            => false,
			'ajax'          => false,
			'return_format' => 'value',
		] );

		return $this->add_fields_to_parent( $group, $fields );
	}

	protected function get_taxonomy_filter_group(): Has_Sub_Fields {
		$group = new Field_Group( $this->build_field_key( self::GROUP_TAXONOMIES ), [
			'label'             => esc_html__( 'Filter By Taxonomy Terms', 'tribe' ),
			'name'              => self::GROUP_TAXONOMIES,
			'conditional_logic' => [
				[
					[
						'field'    => $this->get_key_with_prefix( $this->build_field_key( self::QUERY_TYPE ) ),
						'operator' => '==',
						'value'    => self::QUERY_TYPE_AUTO,
					],
				],
			],
		] );

		return $this->add_fields_to_parent( $group, $this->build_taxonomy_fields() );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	protected function build_taxonomy_fields(): array {
		$fields     = [];
		$taxonomies = acf_get_taxonomy_labels( $this->config->taxonomies );

		asort( $taxonomies );

		foreach ( $taxonomies as $taxonomy => $label ) {
			$fields[] = new Field( $this->build_field_key( $taxonomy ), [
				'label'         => sprintf( esc_html__( 'Filter By %s', 'tribe' ), $label ),
				'name'          => $taxonomy,
				'type'          => 'taxonomy',
				'taxonomy'      => $taxonomy,
				'field_type'    => 'multi_select',
				'add_term'      => false,
				'save_terms'    => false,
				'load_terms'    => false,
				'multiple'      => true,
				'allow_null'    => true,
				'return_format' => 'id',
			] );
		}

		return $fields;
	}

	protected function get_manual_query_repeater(): Has_Sub_Fields {
		$repeater = new Repeater( $this->build_field_key( self::MANUAL_POSTS ), [
			'label'             => esc_html__( 'Manual Posts', 'tribe' ),
			'name'              => self::MANUAL_POSTS,
			'type'              => 'repeater',
			'layout'            => 'row',
			'min'               => $this->config->limit_min,
			'max'               => $this->config->limit_max,
			'button_label'      => $this->config->button_label,
			'conditional_logic' => [
				[
					[
						'field'    => $this->get_key_with_prefix( $this->build_field_key( self::QUERY_TYPE ) ),
						'operator' => '==',
						'value'    => self::QUERY_TYPE_MANUAL,
					],
				],
			],
		] );

		$fields = [];

		$fields[] = new Field( $this->build_field_key( self::MANUAL_POST ), [
			'label'      => esc_html__( 'Post Selection', 'tribe' ),
			'name'       => self::MANUAL_POST,
			'type'       => 'post_object',
			'allow_null' => true,
			'post_type'  => $this->config->post_types_manual,
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_TOGGLE ), [
			'label' => esc_html__( 'Create or Override Content', 'tribe' ),
			'name'  => self::MANUAL_TOGGLE,
			'type'  => 'true_false',
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_TITLE ), [
			'label'             => esc_html__( 'Post Title', 'tribe' ),
			'name'              => self::MANUAL_TITLE,
			'type'              => 'text',
			'conditional_logic' => [
				[
					'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
					'operator' => '==',
					'value'    => '1',
				],
			],
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_POST_DATE ), [
			'label'             => esc_html__( 'Post Date', 'tribe' ),
			'name'              => self::MANUAL_POST_DATE,
			'type'              => 'date_time_picker',
			'instructions'      => esc_html__( 'Set or override the post date.', 'tribe' ),
			'display_format'    => 'F j, Y g:i a',
			'return_format'     => 'Y-m-d H:i:s',
			'first_day'         => true,
			'conditional_logic' => [
				[
					'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
					'operator' => '==',
					'value'    => '1',
				],
			],
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_POST_AUTHOR ), [
			'label'             => esc_html__( 'Post Author', 'tribe' ),
			'name'              => self::MANUAL_POST_AUTHOR,
			'type'              => 'user',
			'instructions'      => esc_html__( 'Set or override the post author.', 'tribe' ),
			'required'          => false,
			'role'              => [], // an array of roles to filter by
			'allow_null'        => true,
			'multiple'          => false,
			'return_format'     => 'id', // array, object, id
			'conditional_logic' => [
				[
					'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
					'operator' => '==',
					'value'    => '1',
				],
			],
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_POST_CATEGORY ), [
			'label'             => esc_html__( 'Post Category', 'tribe' ),
			'name'              => self::MANUAL_POST_CATEGORY,
			'type'              => 'taxonomy',
			'instructions'      => esc_html__( 'Set or override the primary post category.', 'tribe' ),
			'taxonomy'          => Category::NAME,
			'field_type'        => 'select',
			'add_term'          => false,
			'save_terms'        => false,
			'load_terms'        => false,
			'multiple'          => false,
			'allow_null'        => true,
			'return_format'     => 'id',
			'conditional_logic' => [
				[
					'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
					'operator' => '==',
					'value'    => '1',
				],
			],
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_EXCERPT ), [
			'label'             => esc_html__( 'Post Excerpt', 'tribe' ),
			'name'              => self::MANUAL_EXCERPT,
			'type'              => 'textarea',
			'conditional_logic' => [
				[
					'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
					'operator' => '==',
					'value'    => '1',
				],
			],
		] );

		$fields[] = $this->get_cta_field( $this->config->field_name, [
			[
				'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
				'operator' => '==',
				'value'    => '1',
			],
		] );

		$fields[] = new Field( $this->build_field_key( self::MANUAL_IMAGE ), [
			'label'             => esc_html__( 'Image', 'tribe' ),
			'name'              => self::MANUAL_IMAGE,
			'type'              => 'image',
			'return_format'     => 'array',
			'instructions'      => $this->config->image_instructions,
			'conditional_logic' => [
				[
					'field'    => $this->get_key_with_prefix( $this->build_field_key( self::MANUAL_TOGGLE ) ),
					'operator' => '==',
					'value'    => '1',
				],
			],
		] );

		return $this->add_fields_to_parent( $repeater, $fields );
	}

	protected function get_key_with_prefix( string $name ): string {
		return sprintf( '%s_%s', 'field', $name );
	}

	/**
	 * Assign subfields to a parent field.
	 *
	 * @param \Tribe\Libs\ACF\Contracts\Has_Sub_Fields $parent
	 * @param array                                    $fields
	 *
	 * @return \Tribe\Libs\ACF\Contracts\Has_Sub_Fields
	 */
	protected function add_fields_to_parent( Has_Sub_Fields $parent, array $fields ): Has_Sub_Fields {
		foreach ( $fields as $field ) {
			// Skip fields the developer has said to hide for this block.
			if ( $this->is_hidden_field( $field ) ) {
				continue;
			}

			$parent->add_field( $field );
		}

		return $parent;
	}

	/**
	 * Hidden fields will not be added to this instance of the definition.
	 *
	 * @param \Tribe\Libs\ACF\Field $field
	 *
	 * @return bool
	 */
	protected function is_hidden_field( Field $field ): bool {
		$hidden_fields = $this->config->hide_fields;

		return isset( $hidden_fields[ $field->get( 'name' ) ] );
	}

	/**
	 * Build a unique field key based on the block and the provided group.
	 *
	 * @param string $name The field name.
	 *
	 * @return string The formatted field key.
	 */
	protected function build_field_key( string $name ): string {
		return sprintf( '%s_%s_%s', $this->config->block_name, $this->config->field_name, $name );
	}

}
