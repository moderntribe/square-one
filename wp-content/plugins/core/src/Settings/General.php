<?php

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;

class General extends ACF_Settings {

	const ADDRESS = 'contact_address';
	const PHONE   = 'contact_phone';
	const EMAIL   = 'contact_email';

	public function get_title() {
		return 'General Settings';
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	public function register_fields() {
		acf_add_local_field_group( $this->get_contact_info_box() );
	}

	private function get_contact_info_box() {
		$box = new Group( 'contact_info' );
		$box->set_attributes( [
			'title'    => __( 'Contact Info', 'tribe' ),
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $this->slug,
					],
				],
			],
		] );
		$box->add_field( $this->get_address_field() );
		$box->add_field( $this->get_phone_field() );
		$box->add_field( $this->get_email_field() );

		return $box->get_attributes();
	}

	private function get_address_field() {
		$field = new Field( 'contact_info_address' );
		$field->set_attributes( [
			'label' => __( 'Address', 'tribe' ),
			'type'  => 'textarea',
			'rows'  => 4,
			'name'  => static::ADDRESS,
		] );

		return $field;
	}

	private function get_phone_field() {
		$field = new Field( 'contact_info_phone' );
		$field->set_attributes( [
			'label' => __( 'Telephone', 'tribe' ),
			'type'  => 'text',
			'name'  => static::PHONE,
		] );

		return $field;
	}

	private function get_email_field() {
		$field = new Field( 'contact_info_email' );
		$field->set_attributes( [
			'label' => __( 'Email', 'tribe' ),
			'type'  => 'email',
			'name'  => static::EMAIL,
		] );

		return $field;
	}
}