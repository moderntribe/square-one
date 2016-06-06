<?php

/**
 * Forms
 */

class Forms {

	public function __construct() {

		add_filter( 'gform_enqueue_scripts', [ $this, 'enqueue_gravity_forms_jquery_ui_styles' ] );
		add_filter( 'gform_field_css_class', [ $this, 'add_gf_select_field_class' ], 10, 3 );
		add_filter( 'gform_pre_render', [ $this, 'deactivate_gf_animations' ] );
		add_filter( 'gform_confirmation_anchor', '__return_false' );

	}

	/**
	 * Enqueue styles for datepicker on Gravity Forms
	 */

	public function enqueue_gravity_forms_jquery_ui_styles() {

		global $wp_scripts;
		$jquery_ui = $wp_scripts->query( 'jquery-ui-core' );
		wp_enqueue_style( 'jquery-ui-smoothness', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_ui->ver . '/themes/smoothness/jquery-ui.css', false, 'screen' );

	}

	/**
	 * Add a custom class to the Gravity Forms select field
	 * @link http://www.gravityhelp.com/documentation/page/Gform_field_css_class
	 */

	public function add_gf_select_field_class( $classes, $field, $form ) {

		if( $field['type'] == 'multiselect' || ( $field['type'] == 'post_custom_field' && $field['inputType'] == 'multiselect' ) ) {

			$classes .= ' gf-multi-select';

		} elseif( $field['type'] == 'select' || ( $field['type'] == 'post_custom_field' && $field['inputType'] == 'select' ) ) {

			$classes .= ' gf-select';

			// Not Chosen, regular select
			if( ! $field['enableEnhancedUI'] ) {
				$classes .= ' gf-select-no-chosen';
			}

		} elseif( $field['type'] == 'checkbox' ) {

			$classes .= ' gf-checkbox';

		} elseif( $field['type'] == 'radio' ) {

			$classes .= ' gf-radio';

		} elseif( $field['type'] == 'textarea' ) {

			$classes .= ' gf-textarea';

		} elseif( $field['type'] == 'date' ) {

			$classes .= ' gf-date gf-date-layout-'. $field['dateType'];

		} elseif( $field['type'] == 'time' ) {

			$classes .= ' gf-time';

		} elseif( $field['type'] == 'phone' ) {

			$classes .= ' gf-phone';

		} elseif( $field['type'] == 'name' ) {

			$classes .= ' gf-name';

		} elseif( $field['type'] == 'address' ) {

			$classes .= ' gf-address';

		} elseif( $field['type'] == 'email' ) {

			$classes .= ' gf-email';

		} elseif( $field['type'] == 'website' ) {

			$classes .= ' gf-url';

		} elseif( $field['type'] == 'fileupload' ) {

			$classes .= ' gf-file';

		}

		return $classes;

	}

	/**
	 * Set enableAnimation for all forms to be false always. Removes a weird
	 * collision between Gravity and GSAP jQuery plugin, our JS animation library, which is
	 * worth the performance gain in animations used site wide over the Gravity
	 * Forms conditional animations, which look terrible anyway.
	 */

	public function deactivate_gf_animations( $form = [] ) {

		if ( isset( $form['enableAnimation'] ) && $form['enableAnimation'] == true ) {
			$form['enableAnimation'] = false;
		}

		return $form;

	}

}

new Forms();
