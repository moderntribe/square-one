<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF\Field;
use Tribe\Project\Object_Meta\Contracts\Abstract_Tab;

class Social_Settings extends Abstract_Tab {

	public const NAME = 'social_settings';

	public const SOCIAL_FACEBOOK  = 'social_facebook';
	public const SOCIAL_TWITTER   = 'social_twitter';
	public const SOCIAL_YOUTUBE   = 'social_youtube';
	public const SOCIAL_LINKEDIN  = 'social_linkedin';
	public const SOCIAL_PINTEREST = 'social_pinterest';
	public const SOCIAL_INSTAGRAM = 'social_instagram';

	public function get_key(): string {
		return self::NAME;
	}

	public function get_title(): string {
		return esc_html__( 'Social Media', 'tribe' );
	}

	/**
	 * @return \Tribe\Libs\ACF\Field[]
	 */
	public function get_fields(): array {
		return [
			$this->get_social_field( esc_html__( 'Facebook', 'tribe' ), self::SOCIAL_FACEBOOK ),
			$this->get_social_field( esc_html__( 'Twitter', 'tribe' ), self::SOCIAL_TWITTER ),
			$this->get_social_field( esc_html__( 'LinkedIn', 'tribe' ), self::SOCIAL_LINKEDIN ),
			$this->get_social_field( esc_html__( 'Pinterest', 'tribe' ), self::SOCIAL_PINTEREST ),
			$this->get_social_field( esc_html__( 'YouTube', 'tribe' ), self::SOCIAL_YOUTUBE ),
			$this->get_social_field( esc_html__( 'Instagram', 'tribe' ), self::SOCIAL_INSTAGRAM ),
		];
	}

	/**
	 * @return string[]
	 */
	public function get_keys(): array {
		return [
			static::SOCIAL_FACEBOOK,
			static::SOCIAL_TWITTER,
			static::SOCIAL_YOUTUBE,
			static::SOCIAL_LINKEDIN,
			static::SOCIAL_PINTEREST,
			static::SOCIAL_INSTAGRAM,
		];
	}

	private function get_social_field( string $field_label, string $field_id, string $type = 'url' ): Field {
		return new Field( self::NAME . '_' . $field_id, [
			'label' => $field_label,
			'name'  => $field_id,
			'type'  => $type,
		] );
	}

}
