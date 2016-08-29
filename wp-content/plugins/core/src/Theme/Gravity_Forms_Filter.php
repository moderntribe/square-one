<?php

namespace Tribe\Project\Theme;

/**
 * Class Forms
 *
 * Functions for handling forms, namely Gravity Forms
 *
 * @package Tribe\Project\Theme
 */
class Gravity_Forms_Filter {

	/**
	 * @var bool Used to enable/disable CSS classes that control icon placement inside some field types.
	 */
	private $activate_icons = false;

	public function hook() {

		add_filter( 'gform_enqueue_scripts', [ $this, 'enqueue_gravity_forms_jquery_ui_styles' ] );
		add_filter( 'gform_field_choice_markup_pre_render', [ $this, 'customize_gf_choice_other' ], 10, 4 );
		add_filter( 'gform_field_css_class', [ $this, 'add_gf_select_field_class' ], 10, 3 );
		add_filter( 'gform_pre_render', [ $this, 'deactivate_gf_animations' ] );
		add_filter( 'gform_confirmation_anchor', '__return_false' );
		add_filter( 'gform_tabindex', '__return_false' );
	}

	/**
	 * Enqueue styles for datepicker on Gravity Forms
	 */
	public function enqueue_gravity_forms_jquery_ui_styles() {

		global $wp_scripts;
		$jquery_ui = $wp_scripts->query( 'jquery-ui-core' );
		wp_enqueue_style( 'jquery-ui-smoothness',
			'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_ui->ver . '/themes/smoothness/jquery-ui.css',
			false, 'screen' );

	}

	/**
	 * Add some custom markup to other option for radio & checkbox controls
	 *
	 * @link https://www.gravityhelp.com/documentation/article/gform_field_choice_markup_pre_render/
	 */
	public function customize_gf_choice_other( $choice_markup, $choice, $field, $value ) {

		if ( ! empty( $choice['isOtherChoice'] ) ) {

			$indices = array_keys( $field['choices'] );
			$index   = array_pop( $indices );

			$new_markup = sprintf( '<label for="choice_%1$s_%2$s_%3$s" class="gf-radio-checkbox-other-placeholder"><span class="u-visual-hide">%4$s</span></label></li>',
				$field['formId'], $field['id'], $index, __( 'Other', 'tribe' ) );

			$choice_markup = str_replace( '</li>', $new_markup, $choice_markup );

		}

		return $choice_markup;

	}

	/**
	 * Add a custom class to the Gravity Forms select field
	 *
	 * @link http://www.gravityhelp.com/documentation/page/Gform_field_css_class
	 */
	public function add_gf_select_field_class( $classes, $field, $form ) {

		$class_icon_simple  = $this->activate_icons ? ' form-control-icon' : '';
		$class_icon_complex = $this->activate_icons ? ' form-control-icon-complex' : '';

		if ( $field['type'] === 'multiselect' || ( $field['type'] === 'post_custom_field' && $field['inputType'] === 'multiselect' ) ) {
			$classes .= ' gf-multi-select';
		} elseif ( $field['type'] === 'select' || ( $field['type'] === 'post_custom_field' && $field['inputType'] === 'select' ) ) {
			$classes .= ' gf-select';
			// Not Chosen, regular select
			if ( ! $field['enableEnhancedUI'] ) {
				$classes .= ' gf-select-no-chosen';
			}
		} elseif ( $field['type'] === 'checkbox' ) {
			$classes .= ' gf-checkbox';
		} elseif ( $field['type'] === 'radio' ) {
			$classes .= ' gf-radio';
		} elseif ( $field['type'] === 'textarea' ) {
			$classes .= ' gf-textarea';
		} elseif ( $field['type'] === 'date' ) {
			$class_date_icon = ( $field['dateType'] === 'datepicker' ) ? $class_icon_simple : '';
			$classes .= ' gf-date gf-date-layout-' . $field['dateType'] . $class_date_icon;
		} elseif ( $field['type'] === 'time' ) {
			$classes .= ' gf-time';
		} elseif ( $field['type'] === 'phone' ) {
			$classes .= ' gf-phone' . $class_icon_simple;
		} elseif ( $field['type'] === 'name' ) {
			$classes .= ' gf-name' . $class_icon_complex;
		} elseif ( $field['type'] === 'address' ) {
			$classes .= ' gf-address' . $class_icon_complex;
		} elseif ( $field['type'] === 'email' ) {
			$classes .= ' gf-email' . $class_icon_simple;
		} elseif ( $field['type'] === 'website' ) {
			$classes .= ' gf-url' . $class_icon_simple;
		} elseif ( $field['type'] === 'fileupload' ) {
			$classes .= ' gf-file';
		} elseif ( $field['enablePasswordInput'] === true ) {
			$classes .= ' gf-password' . $class_icon_simple;
		}

		return $classes;

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
	 */
	public function deactivate_gf_animations( $form = [] ) {

		if ( isset( $form['enableAnimation'] ) && $form['enableAnimation'] == true ) {
			$form['enableAnimation'] = false;
		}

		return $form;
	}

}
