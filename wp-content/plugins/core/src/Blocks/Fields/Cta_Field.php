<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Fields;

use Tribe\Libs\ACF\Field_Group;

interface Cta_Field {

	public const GROUP_CTA      = 'g-cta';
	public const LINK           = 'link';
	public const ADD_ARIA_LABEL = 'add_aria_label';
	public const ARIA_LABEL     = 'aria_label';

	/**
	 * Get Cta Field
	 *
	 * @param string $block_name
	 *
	 * @return \Tribe\Libs\ACF\Field_Group
	 */
	public function get_cta_field( string $block_name ): Field_Group;

}
