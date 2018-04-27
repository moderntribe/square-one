<?php

namespace Tribe\Project\Syndicate\Admin;

use Tribe\Libs\ACF;
use Tribe\Project\Post_Types\Page\Page;

class Settings_Fields extends ACF\ACF_Meta_Group {

	const NAME = 'syndication_settings';

	const STATE = 'post_states';
	const TYPE  = 'post_types';

	const DISPLAY_EXCLUDED_POST_TYPES = [
		'nav_menu_item',
		'custom_css',
		'attachment',
		'revision',
		'customize_changeset',
	];

	const DISPLAY_EXCLUDED_POST_STATES = [
		'private',
		'auto-draft',
		'inherit',
		'trash',
	];

	const REQUIRED_POST_TYPES = [
		'attachment',
	];

	const REQUIRED_POST_STATES = [
		'inherit',
	];

	public function get_keys() {
		return [
			self::STATE,
			self::TYPE,
		];
	}

	protected function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Syndication Settings', 'tribe' ) );

		$group->add_field( $this->get_post_types_field() );
		$group->add_field( $this->get_post_status_field() );

		return $group->get_attributes();
	}

	private function get_post_types_field() {
		$field = new ACF\Field( self::NAME . '_' . self::TYPE );
		$field->set_attributes( [
			'label'   => __( 'Post Types to Syndicate', 'tribe' ),
			'name'    => self::NAME . '_' . self::TYPE,
			'type'    => 'checkbox',
			'choices' => $this->get_post_types_list(),
		] );

		return $field;
	}

	private function get_post_types_list() {
		$post_types = get_post_types();

		return array_diff( $post_types, self::DISPLAY_EXCLUDED_POST_TYPES );
	}

	private function get_post_status_field() {
		$field = new ACF\Field( self::NAME . '_' . self::STATE );
		$field->set_attributes( [
			'label'   => __( 'Post Status to Syndicate', 'tribe' ),
			'name'    => self::NAME . '_' . self::STATE,
			'type'    => 'checkbox',
			'choices' => $this->get_post_status_list(),
		] );

		return $field;
	}

	private function get_post_status_list() {
		$states = get_post_stati();

		return array_diff( $states, self::DISPLAY_EXCLUDED_POST_STATES );
	}

	public static function types() {
		$types = get_field( self::NAME . '_' . self::TYPE, 'option' );
		if ( ! is_array( $types ) ) {
			$types = [
				Page::NAME,
			];
		}

		return array_unique( array_merge( $types, self::REQUIRED_POST_TYPES ) );
	}

	public static function states() {
		$types = get_field( self::NAME . '_' . self::STATE, 'option' );
		if ( ! is_array( $types ) ) {
			$types = [
				'publish',
			];
		}
		return array_unique( array_merge( $types, self::REQUIRED_POST_STATES ) );
	}

	public function option_update() {
		$screen   = get_current_screen();
		$settings = Settings::instance();
		if ( strpos( $screen->id, $settings->get_slug() ) == true) {
			do_action('syndicate/alter_views' );
			wp_safe_redirect( network_admin_url( 'admin.php?page=elasticpress&do_sync=1' ) );
			exit;
		}
	}
}