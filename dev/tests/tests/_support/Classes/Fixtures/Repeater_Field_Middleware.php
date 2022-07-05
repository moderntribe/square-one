<?php declare(strict_types=1);

namespace Tribe\Tests\Fixtures;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;
use Tribe\Project\Block_Middleware\Traits\With_Field_Finder;

class Repeater_Field_Middleware extends Abstract_Field_Middleware {

	use With_Field_Finder;

	public const FIELD = 'title';

	protected function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		[ $parent_key ] = $params;

		$fields     = $block->get_fields();
		$block_name = $block->get_block()->get_attributes()['name'];
		$parent     = $this->find_field( $fields, $parent_key );

		if ( $parent ) {
			$parent->add_field( new Field( $block_name . '_' . self::FIELD, [
				'name' => self::FIELD,
				'type' => 'text',
			] ) );
		}

		$block->set_fields( $fields );

		return $block;
	}

}
