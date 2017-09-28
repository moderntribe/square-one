<?php

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;

class Places_Settings extends ACF_Settings {

	const NAME = 'places_api_settings';
	const API_KEY = 'api_key';

	public function get_title() {
		return __( 'Places', 'tribe' );
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	public function register_fields() {
		acf_add_local_field_group( $this->get_settings_group() );
	}

	private function get_settings_group() {
		$key   = self::NAME;
		$group = new Group( $key );
		$group->set_attributes(
			[
				'title' => __( 'Google Places API Stuff', 'tribe' ),
				'location' => [
					[
						[
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => $this->slug,
						],
					],
				],
			]
		);
		$group->add_field( $this->get_API_field() );

		return $group->get_attributes();
	}

	private function get_API_field() {
		$field = new Field( self::NAME . '_' . self::API_KEY );
		$field->set_attributes( [
			'label'   => __( 'API Key', 'tribe' ),
			'name'    => self::API_KEY,
			'type'    => 'text',
		] );
		return $field;
	}

}