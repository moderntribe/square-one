<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF\Field;
use Tribe\Project\Object_Meta\Contracts\Abstract_Tab;

class Analytics_Settings extends Abstract_Tab {

	public const NAME = 'analytic_settings';

	public const ANALYTICS_GTM_ID = 'analytics_gtm_id';

	public function get_key(): string {
		return self::NAME;
	}

	public function get_title(): string {
		return esc_html__( 'Analytics', 'tribe' );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	public function get_fields(): array {
		return [
			$this->get_analytics_gtm_id_field(),
		];
	}

	public function get_keys(): array {
		return [
			self::ANALYTICS_GTM_ID,
		];
	}

	private function get_analytics_gtm_id_field(): Field {
		$field = new Field( self::NAME . '_' . self::ANALYTICS_GTM_ID );
		$field->set_attributes( [
			'label'       => esc_html__( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::ANALYTICS_GTM_ID,
			'type'        => 'text',
			'placeholder' => esc_attr__( 'Enter Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
		] );

		return $field;
	}

}
