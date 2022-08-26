<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta\Contracts;

use Tribe\Libs\ACF;

abstract class Abstract_Settings_With_Tab extends ACF\ACF_Meta_Group implements With_Title, With_Key {

	/**
	 * @var \Tribe\Project\Object_Meta\Contracts\Abstract_Tab[]
	 */
	protected array $settings;

	public function __construct( array $object_types, array $settings ) {
		parent::__construct( $object_types );

		$this->settings = $settings;
	}

	/**
	 * @return string[]
	 */
	public function get_keys(): array {
		$keys = [];

		foreach ( $this->settings as $setting ) {
			$keys = array_merge( $keys, $setting->get_keys() );
		}

		return $keys;
	}

	protected function get_group_config(): array {
		$group = new ACF\Group( $this->get_key(), $this->object_types );
		$group->set( 'title', $this->get_title() );

		foreach ( $this->settings as $field_group ) {
			if ( ! $field_group->get_fields() ) {
				continue;
			}

			$tab = new ACF\Field( $this->get_key() . '_' . $field_group->get_key() );
			$tab->set_attributes( [
				'label' => $field_group->get_title(),
				'name'  => $field_group->get_key(),
				'type'  => 'tab',
			] );

			$group->add_field( $tab );

			foreach ( $field_group->get_fields() as $field ) {
				$group->add_field( $field );
			}
		}

		return $group->get_attributes();
	}

}
