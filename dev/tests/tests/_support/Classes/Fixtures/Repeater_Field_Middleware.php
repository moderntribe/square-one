<?php declare(strict_types=1);

namespace Tribe\Tests\Fixtures;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Traits\With_Field_Finder;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;

/**
 * Fixture to mimic the functionality of a repeater like middleware.
 */
class Repeater_Field_Middleware extends Abstract_Field_Middleware {

	use With_Field_Finder;

	public const FIELD          = 'title';
	public const MIDDLEWARE_KEY = 'test_unique_key';

	protected function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		$parent_keys = $params[ self::MIDDLEWARE_KEY ] ?? [];

		if ( ! $parent_keys || ! is_array( $parent_keys ) ) {
			return $block;
		}

		$fields = $block->get_fields();

		foreach ( $parent_keys as $parent_key ) {
			$block_name = $block->get_block()->get_attributes()['name'];
			$parent_field     = $this->find_field( $fields, $parent_key );

			if ( ! $parent_field ) {
				continue;
			}

			$parent_field->add_field( new Field( $block_name . '_' . self::FIELD, [
				'name' => self::FIELD,
				'type' => 'text',
			] ) );
		}

		return $block->set_fields( $fields );
	}

}
