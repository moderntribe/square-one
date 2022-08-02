<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Field_Middleware;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Traits\With_Field_Finder;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;

/**
 * Inject a color theme field inside a parent ACF field, e.g. an ACF Repeater, Group or Section.
 */
class Color_Theme_Repeater_Field_Middleware extends Abstract_Field_Middleware implements Appearance {

	use With_Field_Finder;

	public const MIDDLEWARE_KEY = 'color_theme_parent_keys';

	protected Color_Theme_Field $color_theme;

	public function __construct( Block_Field_Middleware_Guard $guard, Color_Theme_Field $color_theme ) {
		parent::__construct( $guard );

		$this->color_theme = $color_theme;
	}

	/**
	 * @param \Tribe\Libs\ACF\Block_Config      $block
	 * @param array{color_theme_parent_key?: string[]} $params
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	protected function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		$parent_keys = $params[ self::MIDDLEWARE_KEY ] ?? [];

		if ( ! $parent_keys || ! is_array( $parent_keys ) ) {
			return $block;
		}

		$fields = $block->get_fields();

		foreach ( $parent_keys as $parent_key ) {
			$block_name   = $block->get_block()->get_attributes()['name'];
			$parent_field = $this->find_field( $fields, $parent_key );

			if ( ! $parent_field ) {
				continue;
			}

			$parent_field->add_field( $this->color_theme->get_field(
				$block_name,
				esc_html__( 'Color Theme', 'tribe' )
			) );
		}

		return $block->set_fields( $fields );
	}

}
