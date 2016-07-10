<?php
namespace Tribe\Project\Settings;

class General extends Abstract_Settings {

	const DEMO_SETTING_ONE = 'demo_setting_one';
	const DEMO_SETTING_TWO = 'demo_setting_two';

	public function get_title() {
		return 'General Settings';
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	protected function get_fields() {
		return [
			[
				'key'               => 'field_576ae96f654cf',
				'label'             => 'Demo Setting One',
				'name'              => self::DEMO_SETTING_ONE,
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => '',
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'maxlength'         => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
			[
				'key'               => 'field_576ae9ae654d0',
				'label'             => 'Demo Setting Two',
				'name'              => self::DEMO_SETTING_TWO,
				'type'              => 'number',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'default_value'     => 5,
				'placeholder'       => '',
				'prepend'           => '',
				'append'            => '',
				'min'               => '',
				'max'               => '',
				'step'              => '',
				'readonly'          => 0,
				'disabled'          => 0,
			],
		];
	}
}