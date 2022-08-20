<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Fields;

use Tribe\Libs\ACF\Field_Group;

interface Cta_Field {

	public const GROUP_CTA      = 'cta';
	public const LINK           = 'link';
	public const ADD_ARIA_LABEL = 'add_aria_label';
	public const ARIA_LABEL     = 'aria_label';

	/**
	 * Get Cta Field
	 *
	 * @param string $block_name The Block::NAME constant or a repeater parent.
	 * @param array  $conditional_logic ACF conditional logic array to only appear under some condition.
	 *
	 * @return \Tribe\Libs\ACF\Field_Group
	 */
	public function get_cta_field( string $block_name, array $conditional_logic = [] ): Field_Group;

	/**
	 * Returns just the subfields for the CTA if they won't be used in a field group.
	 *
	 * @param string $block_name
	 *
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	public function get_cta_sub_fields( string $block_name ): array;

}
