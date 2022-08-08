<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Model_Middleware;

use Tribe\Libs\ACF\Traits\With_Get_Field;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Model_Middleware;
use Tribe\Project\Block_Middleware\Guards\Block_Model_Middleware_Guard;
use Tribe\Project\Blocks\Contracts\Model;
use Tribe\Project\Blocks\Middleware\Color_Theme\Class_Manager;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;

class Color_Theme_Field_Model_Middleware extends Abstract_Model_Middleware {

	use With_Get_Field;

	public const CLASSES = 'classes';

	protected Class_Manager $class_manager;

	public function __construct( Block_Model_Middleware_Guard $guard, Class_Manager $class_manager ) {
		parent::__construct( $guard );

		$this->class_manager = $class_manager;
	}

	/**
	 * Add the color theme CSS class to the model, so it's passed
	 * down to the block controller's $classes property.
	 *
	 * @param \Tribe\Project\Blocks\Contracts\Model $model
	 *
	 * @return \Tribe\Project\Blocks\Contracts\Model
	 */
	protected function append_data( Model $model ): Model {
		$theme = $this->get( Appearance::COLOR_THEME, '' );

		$data = [
			self::CLASSES => [
				$this->class_manager->get_class( $theme ),
			],
		];

		return $model->set_data( array_merge_recursive( $model->get_data(), $data ) );
	}

}
