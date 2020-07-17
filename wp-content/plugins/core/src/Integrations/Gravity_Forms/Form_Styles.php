<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Gravity_Forms;

class Form_Styles {

	/**
	 * Enqueue styles for datepicker on Gravity Forms
	 *
	 * @action gform_enqueue_scripts
	 */
	public function enqueue_gravity_forms_jquery_ui_styles(): void {
		global $wp_scripts;
		$jquery_ui = $wp_scripts->query( 'jquery-ui-core' );
		wp_enqueue_style(
			'jquery-ui-smoothness',
			'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_ui->ver . '/themes/smoothness/jquery-ui.css',
			false,
			'screen'
		);
	}

	/**
	 * Dequeue styles for formsmain on Gravity Forms.
	 * This prevents GF styles from interferring with our sink form styles.
	 *
	 * @action gform_enqueue_scripts
	 */
	public function dequeue_gravity_forms_formsmain_styles(): void {
		global $wp_styles;
		if ( isset ( $wp_styles->registered['gforms_formsmain_css'] ) ) {
			unset( $wp_styles->registered['gforms_formsmain_css'] );
		}
	}

	/**
	 * Set enableAnimation for all forms to be false always. Removes a weird
	 * collision between Gravity and GSAP jQuery plugin, our JS animation library, which is
	 * worth the performance gain in animations used site wide over the Gravity
	 * Forms conditional animations, which look terrible anyway.
	 *
	 * @param array $form
	 *
	 * @return array
	 * @filter gform_pre_render
	 */
	public function deactivate_gf_animations( $form = [] ) {

		if ( isset( $form['enableAnimation'] ) && $form['enableAnimation'] == true ) {
			$form['enableAnimation'] = false;
		}

		return $form;
	}
}
