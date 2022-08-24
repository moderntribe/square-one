<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF\Field;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;
use Tribe\Project\Object_Meta\Contracts\Abstract_Tab;

class Footer_Settings extends Abstract_Tab {

	public const NAME = 'footer_settings';

	public const FOOTER_LOGO        = 'footer_logo';
	public const FOOTER_DESCRIPTION = 'footer_description';
	public const FOOTER_CTA_1       = 'footer_cta_1';
	public const FOOTER_CTA_2       = 'footer_cta_2';

	public function get_key(): string {
		return self::NAME;
	}

	public function get_title(): string {
		return esc_html__( 'Site Footer', 'tribe' );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	public function get_fields(): array {
		return [
			$this->get_footer_logo_field(),
			$this->get_footer_description_field(),
			$this->get_footer_cta_field( esc_html__( 'Call To Action 1', 'tribe' ), self::FOOTER_CTA_1 ),
			$this->get_footer_cta_field( esc_html__( 'Call To Action 2', 'tribe' ), self::FOOTER_CTA_2 ),
		];
	}

	/**
	 * @return string[]
	 */
	public function get_keys(): array {
		return [
			static::FOOTER_LOGO,
			static::FOOTER_DESCRIPTION,
			static::FOOTER_CTA_1,
			static::FOOTER_CTA_2,
		];
	}

	private function get_footer_logo_field(): Field {
		$field = new Field( self::NAME . '_' . self::FOOTER_LOGO );
		$field->set_attributes( [
			'label'         => esc_html__( 'Logo', 'tribe' ),
			'name'          => self::FOOTER_LOGO,
			'type'          => 'image',
			'return_format' => 'id',
			'instructions'  => esc_html__( 'Appears at the top of the site footer. Recommended minimum width: 700px. Recommended file type: .svg.', 'tribe' ),
		] );

		return $field;
	}

	private function get_footer_description_field(): Field {
		$field = new Field( self::NAME . '_' . self::FOOTER_DESCRIPTION );
		$field->set_attributes( [
			'label'        => esc_html__( 'Description', 'tribe' ),
			'name'         => self::FOOTER_DESCRIPTION,
			'type'         => 'wysiwyg',
			'toolbar'      => Classic_Editor_Formats::MINIMAL,
			'tabs'         => 'visual',
			'media_upload' => 0,
			'instructions' => esc_html__( 'Appears below the logo in the site footer.', 'tribe' ),
		] );

		return $field;
	}

	private function get_footer_cta_field( string $field_label, string $field_id ): Field {
		$field = new Field( self::NAME . '_' . $field_id );
		$field->set_attributes( [
			'label' => $field_label,
			'name'  => $field_id,
			'type'  => 'link',
		] );

		return $field;
	}

}
