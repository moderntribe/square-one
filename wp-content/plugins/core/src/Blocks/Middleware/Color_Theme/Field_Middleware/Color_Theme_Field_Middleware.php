<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Field_Middleware;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;

class Color_Theme_Field_Middleware extends Abstract_Field_Middleware implements Appearance {

	public const NAME = 'global_color';

	public const SECTION = 's-colors';

	protected Color_Theme_Field $color_theme;

	public function __construct( Block_Field_Middleware_Guard $guard, Color_Theme_Field $color_theme ) {
		parent::__construct( $guard );

		$this->color_theme = $color_theme;
	}

	/**
	 * Add a field section/accordion with an ACF swatch field to the root of a block.
	 *
	 * @param \Tribe\Libs\ACF\Block_Config $block
	 * @param array                        $params
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	public function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		return $block->add_field( new Field_Section( self::SECTION, esc_html__( 'Color Theme', 'tribe' ), 'accordion' ) )
			  ->add_field( $this->color_theme->get_field( self::NAME ) );
	}

}
