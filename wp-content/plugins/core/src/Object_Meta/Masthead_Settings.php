<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF\Field;
use Tribe\Project\Object_Meta\Contracts\Abstract_Tab;

class Masthead_Settings extends Abstract_Tab {

	public const NAME = 'site_header_settings';

	public const MASTHEAD_LOGO = 'masthead_logo';

	public function get_key(): string {
		return self::NAME;
	}

	public function get_title(): string {
		return esc_html__( 'Site Header', 'tribe' );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	public function get_fields(): array {
		return [
			$this->get_logo_field(),
		];
	}

	/**
	 * @return string[]
	 */
	public function get_keys(): array {
		return [
			static::MASTHEAD_LOGO,
		];
	}

	private function get_logo_field(): Field {
		$field = new Field( self::NAME . '_' . self::MASTHEAD_LOGO );
		$field->set_attributes( [
			'label'         => esc_html__( 'Logo', 'tribe' ),
			'name'          => self::MASTHEAD_LOGO,
			'type'          => 'image',
			'return_format' => 'id',
			'instructions'  => esc_html__( 'Appears in the site masthead. Recommended minimum width: 700px. Recommended file type: .svg.', 'tribe' ),
		] );

		return $field;
	}

}
