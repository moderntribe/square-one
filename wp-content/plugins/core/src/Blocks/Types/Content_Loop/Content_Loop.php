<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Content_Loop;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Traits\With_Field_Prefix;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Block_Middleware\Contracts\Has_Middleware_Params;
use Tribe\Project\Blocks\Block_Category;
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
			'title'    => esc_html__( 'Content Loop', 'tribe' ),
			'icon'     => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18.1823 11.6392C18.1823 13.0804 17.0139 14.2487 15.5727 14.2487C14.3579 14.2487 13.335 13.4179 13.0453 12.2922L13.0377 12.2625L13.0278 12.2335L12.3985 10.377L12.3942 10.3785C11.8571 8.64997 10.246 7.39405 8.33961 7.39405C5.99509 7.39405 4.09448 9.29465 4.09448 11.6392C4.09448 13.9837 5.99509 15.8843 8.33961 15.8843C8.88499 15.8843 9.40822 15.781 9.88943 15.5923L9.29212 14.0697C8.99812 14.185 8.67729 14.2487 8.33961 14.2487C6.89838 14.2487 5.73003 13.0804 5.73003 11.6392C5.73003 10.1979 6.89838 9.02959 8.33961 9.02959C9.55444 9.02959 10.5773 9.86046 10.867 10.9862L10.8772 10.9836L11.4695 12.7311C11.9515 14.546 13.6048 15.8843 15.5727 15.8843C17.9172 15.8843 19.8178 13.9837 19.8178 11.6392C19.8178 9.29465 17.9172 7.39404 15.5727 7.39404C15.0287 7.39404 14.5066 7.4968 14.0264 7.6847L14.6223 9.20781C14.9158 9.093 15.2358 9.02959 15.5727 9.02959C17.0139 9.02959 18.1823 10.1979 18.1823 11.6392Z" fill="#1E1E1E"/></svg>',
			'keywords' => [ esc_html__( 'content', 'tribe' ), esc_html__( 'loop', 'tribe' ) ],
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
		$this->add_field( new Field( self::NAME . '_' . self::LEADIN, [
				'label' => esc_html__( 'Overline', 'tribe' ),
				'name'  => self::LEADIN,
				'type'  => 'text',
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

		$this->add_section( new Field_Section( self::SECTION_APPEARANCE, esc_html__( 'Settings', 'tribe' ), 'accordion' ) )
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
