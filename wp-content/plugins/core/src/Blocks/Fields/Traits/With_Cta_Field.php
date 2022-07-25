<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Fields\Traits;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Group;

/**
 * Reusable Cta Field for Blocks, should be used Cta_Field.php interface.
 *
 * @package Tribe\Project\Blocks\Fields\Traits
 */
trait With_Cta_Field {

	public function get_cta_field( string $block_name ): Field_Group {
		$group = new Field_Group( $block_name . '_' . self::GROUP_CTA );
		$group->set_attributes( [
			'label'  => esc_html__( 'Call to Action', 'tribe' ),
			'name'   => self::GROUP_CTA,
			'layout' => 'block',
		] );

		$link = new Field( $block_name . '_' . self::LINK, [
			'label'   => esc_html__( 'Call to Action', 'tribe' ),
			'name'    => self::LINK,
			'type'    => 'link',
			'wrapper' => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$screen_reader = new Field( $block_name . '_' . self::ADD_ARIA_LABEL, [
			'label'   => esc_html__( 'Add Screen Reader Text', 'tribe' ),
			'name'    => self::ADD_ARIA_LABEL,
			'type'    => 'true_false',
			'message' => esc_html__( 'Add Screen Reader Text', 'tribe' ),
			'wrapper' => [
				'class' => 'tribe-acf-hide-label',
			],
		] );

		$screen_reader_label = new Field( $block_name . '_' . self::ARIA_LABEL, [
			'label'             => esc_html__( 'Screen Reader Label', 'tribe' ),
			'instructions'      => esc_html__(
				'A custom label for screen readers if the button\'s action or purpose isn\'t easily identifiable.',
				'tribe'
			),
			'name'              => self::ARIA_LABEL,
			'type'              => 'text',
			'conditional_logic' => [
				[
					[
						'field'    => 'field_' . $block_name . '_' . self::ADD_ARIA_LABEL,
						'operator' => '==',
						'value'    => 1,
					],
				],
			],
		] );

		$group->add_field( $link );
		$group->add_field( $screen_reader );
		$group->add_field( $screen_reader_label );

		return $group;
	}

}
