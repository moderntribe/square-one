<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Integrations\Gravity_Forms;

use Tribe\Project\Components\Component;
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
class Choice_Other extends Component {

	public const FORM_ID     = 'form_id';
	public const FIELD_ID    = 'field_id';
	public const FIELD_INDEX = 'field_index';
	public const LABEL       = 'label';
	public const ATTRIBUTES  = 'attr';
	public const CLASSES     = 'classes';

	protected function defaults(): array {
		return [
			self::FORM_ID     => 0,
			self::FIELD_ID    => 0,
			self::FIELD_INDEX => 0,
			self::LABEL       => '',
			self::CLASSES     => [ 'gf-radio-checkbox-other-placeholder' ],
			self::ATTRIBUTES  => [],
		];
	}

	public function init(): array {
		$this->data[ self::ATTRIBUTES ]['for'] = $this->for_attribute();
	}

	private function for_attribute(): string {
		return sprintf( 'choice_%1$s_%2$s_%3$s', $this->data['form_id'], $this->data['field_id'], $this->data['field_index'] );
	}
}
