<?php declare(strict_types=1);

namespace Tribe\Tests\Fixtures;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;

class Subtitle_Field_Middleware extends Abstract_Field_Middleware {

	public const NAME = 'global_fields';

	public const FIELD = 'subtitle';

	protected function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		return $block->add_field( new Field( self::NAME . '_' . self::FIELD, [
			'name' => self::FIELD,
			'type' => 'text',
		] ) );
	}

}
