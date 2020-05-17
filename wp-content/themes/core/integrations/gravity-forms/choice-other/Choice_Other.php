<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Integrations\Gravity_Forms;

use Tribe\Project\Templates\Components\Context;

/**
 * Class Choice_Other
 *
 * @property string|int $form_id
 * @property string|int $field_id
 * @property int        $field_index
 * @property string     $label
 * @property string[]   $attr
 * @property string[]   $classes
 */
class Choice_Other extends Context {
	public const FORM_ID     = 'form_id';
	public const FIELD_ID    = 'field_id';
	public const FIELD_INDEX = 'field_index';
	public const LABEL       = 'label';
	public const ATTRIBUTES  = 'attr';
	public const CLASSES     = 'classes';

	protected $properties = [
		self::FORM_ID     => [
			self::DEFAULT => 0,
		],
		self::FIELD_ID    => [
			self::DEFAULT => 0,
		],
		self::FIELD_INDEX => [
			self::DEFAULT => 0,
		],
		self::LABEL       => [
			self::DEFAULT => '',
		],
		self::CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'gf-radio-checkbox-other-placeholder' ],
		],
		self::ATTRIBUTES  => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function get_data(): array {
		$this->properties[ self::ATTRIBUTES ][ self::MERGE_ATTRIBUTES ]['for'] = $this->for_attribute();

		return parent::get_data();
	}

	private function for_attribute(): string {
		return sprintf( 'choice_%1$s_%2$s_%3$s', $this->form_id, $this->field_id, $this->field_index );
	}
}
