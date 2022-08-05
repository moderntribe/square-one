<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Tribe_Query_Loop;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Blocks\Block_Category;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;
use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\Taxonomies\Category\Category;
use Tribe\Project\Taxonomies\Post_Tag\Post_Tag;

class Tribe_Query_Loop extends Block_Config {

	public const NAME = 'tribequeryloop';

	public const SECTION_CONTENT  = 's-content';
	public const SECTION_SETTINGS = 's-settings';

	public const POST_LIST    = 'post_list';
	public const HIDE_TOPIC   = 'hide_topic';
	public const HIDE_EXCERPT = 'hide_excerpt';

	//Layout Fields
	public const LAYOUT         = 'layout';
	public const LAYOUT_ROW     = 'layout_row';
	public const LAYOUT_FEATURE = 'layout_feature';
	public const LAYOUT_COLUMNS = 'layout_columns';

	//Query Fields
	public const QUERY_GROUP      = 'query_group';
	public const QUERY_LIMIT      = 'query_limit';
	public const QUERY_OFFSET     = 'offset';
	public const QUERY_OFFSET_MAX = 100;
	public const QUERY_TAXONOMIES = 'query_taxonomy_terms';
	public const QUERY_POST_TYPES = 'query_post_types';

	public const ALLOWED_POST_TYPES = [
		Post::NAME,
		Page::NAME,
		Sample::NAME,
	];

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'    => esc_html__( 'Custom Query', 'tribe' ),
			'icon'     => '<svg viewBox="0 0 146.3 106.3" xmlns="http://www.w3.org/2000/svg"><path fill="#16d690" d="M145.2 106.3l-72.6-64L26.5 1.2 0 106.3z"/><path fill="#21a6cb" d="M145.2 106.3H0l72.6-64 46-41.1z"/><path fill="#008f8f" d="M72.6 42.3l72.6 64H0z"/></svg>',
			'keywords' => [ esc_html__( 'Query', 'tribe' ) ],
			'category' => Block_Category::CUSTOM_BLOCK_CATEGORY_SLUG,
			'supports' => [
				'align'  => false,
				'anchor' => true,
				'html'   => false,
			],
		] ) );
	}

	/**
	 * Register Fields for block
	 */
	public function add_fields(): void {
		// Content Fields
		$content = $this->add_section( new Field_Section( self::SECTION_CONTENT, esc_html__( 'Content', 'tribe' ), 'accordion' ) );

		$content->add_field( $this->get_query_group_field() );

		$content->add_field( new Field( self::NAME . '_' . self::HIDE_TOPIC, [
				'label'        => __( 'Hide Topics', 'tribe' ),
				'name'         => self::HIDE_TOPIC,
				'type'         => 'true_false',
				'ui'           => true,
				'instructions' => __( 'This disables displaying and linking to the main categories.', 'tribe' ),
			] )
		);

		$content->add_field( new Field( self::NAME . '_' . self::HIDE_EXCERPT, [
				'label'        => __( 'Hide Excerpts', 'tribe' ),
				'name'         => self::HIDE_EXCERPT,
				'type'         => 'true_false',
				'ui'           => true,
				'instructions' => __( "This disables displaying each card's description.", 'tribe' ),
			] )
		);

		// Setting Fields
		$this->add_section( new Field_Section( self::SECTION_SETTINGS, esc_html__( 'Settings', 'tribe' ), 'accordion' ) )
		->add_field(
			new Field( self::NAME . '_' . self::LAYOUT, [
				'type'          => 'select',
				'name'          => self::LAYOUT,
				'choices'       => [
					self::LAYOUT_ROW     => esc_html__( 'Row', 'tribe' ),
					self::LAYOUT_FEATURE => esc_html__( 'Feature', 'tribe' ),
					self::LAYOUT_COLUMNS => esc_html__( 'Columns', 'tribe' ),
				],
				'default_value' => self::LAYOUT_ROW,
				'multiple'      => 0,
			] )
		);
	}

	private function get_query_group_field(): Field_Group {
		$group = new Field_Group( self::NAME . '_' . self::QUERY_GROUP, [
			'label' => __( 'Build Your Query', 'tribe' ),
			'name'  => self::QUERY_GROUP,
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
				'min'           => 0,
				'max'           => 10,
				'step'          => 1,
				'type'          => 'range',
				'default_value' => 3,
			] )
		)->add_field(
			new Field( self::NAME . '_' . self::QUERY_OFFSET, [
				'label'         => __( 'Offset', 'tribe' ),
				'name'          => self::QUERY_OFFSET,
				'min'           => 0,
				'max'           => self::QUERY_OFFSET_MAX,
				'step'          => 1,
				'type'          => 'range',
				'default_value' => 0,
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
		global $wp_taxonomies;

		$taxonomy_options                   = [];
		$taxonomy_options[ Category::NAME ] = $wp_taxonomies[ Category::NAME ]->label;
		$taxonomy_options[ Post_Tag::NAME ] = $wp_taxonomies[ Post_Tag::NAME ]->label;

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
