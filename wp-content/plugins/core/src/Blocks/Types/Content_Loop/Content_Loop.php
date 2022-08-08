<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Loop;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Traits\With_Field_Prefix;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Block_Middleware\Contracts\Has_Middleware_Params;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;
use Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config;
use Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware\Post_Loop_Field_Middleware;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class Content_Loop extends Block_Config implements Cta_Field, Has_Middleware_Params {

	use With_Cta_Field;
	use With_Field_Prefix;

	public const NAME = 'contentloop';

	public const LEADIN      = 'leadin';
	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';

	public const SECTION_LOOP_ITEMS = 's-loop-items';
	public const POST_LIST          = 'post_list';

	public const SECTION_APPEARANCE = 's-appearance';
	public const LAYOUT             = 'layout';
	public const LAYOUT_ROW         = 'layout_row';
	public const LAYOUT_FEATURE     = 'layout_feature';
	public const LAYOUT_COLUMNS     = 'layout_columns';

	public function add_block(): void {
		$this->set_block( new Block( self::NAME, [
			'title'       => esc_html__( 'Content Loop', 'tribe' ),
			'description' => esc_html__( 'A loop of auto or manual set posts with style options', 'tribe' ),
			'icon'        => '<svg enable-background="new 0 0 146.3 106.3" version="1.1" viewBox="0 0 146.3 106.3" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><style type="text/css">.st0{fill:#16D690;}.st1{fill:#21A6CB;}.st2{fill:#008F8F;}</style><polygon class="st0" points="145.2 106.3 72.6 42.3 26.5 1.2 0 106.3"/><polygon class="st1" points="145.2 106.3 0 106.3 72.6 42.3 118.6 1.2"/><polygon class="st2" points="72.6 42.3 145.2 106.3 0 106.3"/></svg>',
			// TODO: set SVG icon
			'keywords'    => [ esc_html__( 'content', 'tribe' ), esc_html__( 'loop', 'tribe' ) ],
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
	public function add_fields(): void {
		$this->add_field( new Field( self::NAME . '_' . self::LEADIN, [
				'label'       => esc_html__( 'Lead in', 'tribe' ),
				'name'        => self::LEADIN,
				'type'        => 'text',
				'placeholder' => esc_html__( 'Lead in (optional', 'tribe' ),
				'wrapper'     => [
					'class' => 'tribe-acf-hide-label',
				],
			] )
		)->add_field( new Field( self::NAME . '_' . self::TITLE, [
				'label' => esc_html__( 'Title', 'tribe' ),
				'name'  => self::TITLE,
				'type'  => 'text',
			] )
		)->add_field( new Field( self::NAME . '_' . self::DESCRIPTION, [
				'label'        => esc_html__( 'Description', 'tribe' ),
				'name'         => self::DESCRIPTION,
				'type'         => 'wysiwyg',
				'toolbar'      => Classic_Editor_Formats::MINIMAL,
				'tabs'         => 'visual',
				'media_upload' => 0,
			] )
		)->add_field(
			$this->get_cta_field( self::NAME )
		);

		// Post loop fields will be added to this section via block middleware.
		$this->add_section( new Field_Section( self::SECTION_LOOP_ITEMS, esc_html__( 'Loop Items', 'tribe' ), 'accordion' ) );

		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( new Field( self::NAME . '_' . self::LAYOUT, [
					 'label'         => esc_html__( 'Layout', 'tribe' ),
					 'type'          => 'button_group',
					 'name'          => self::LAYOUT,
					 'choices'       => [
						 self::LAYOUT_ROW     => esc_html__( 'Row', 'tribe' ),
						 self::LAYOUT_FEATURE => esc_html__( 'Feature', 'tribe' ),
						 self::LAYOUT_COLUMNS => esc_html__( 'Columns', 'tribe' ),
					 ],
					 'default_value' => self::LAYOUT_ROW,
				 ] )
			 );
	}

	/**
	 * Config the query post loop field for middleware.
	 *
	 * @return array<array{post_loop_field_configs: \Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config[]}>
	 */
	public function get_middleware_params(): array {
		$config             = new Post_Loop_Field_Config();
		$config->field_name = self::POST_LIST;
		$config->group      = $this->get_section_key( self::SECTION_LOOP_ITEMS );
		$config->limit_max  = 10;
		$config->limit_min  = 3;

		$config->post_types = [
			Post::NAME,
			Page::NAME,
		];

		$config->post_types_manual = [
			Post::NAME,
			Page::NAME,
		];

		return [
			[
				Post_Loop_Field_Middleware::MIDDLEWARE_KEY => [
					$config,
				],
			],
		];
	}

}
