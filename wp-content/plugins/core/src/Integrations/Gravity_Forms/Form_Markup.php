<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Gravity_Forms;

class Form_Markup {
	/**
	 * @var bool Used to enable/disable CSS classes that control icon placement inside some field types.
	 */
	private $activate_icons = false;

	/**
	 * Add some custom markup to other option for radio & checkbox controls
	 *
	 * @link   https://docs.gravityforms.com/gform_field_choice_markup_pre_render/
	 * @filter gform_field_choice_markup_pre_render
	 *
	 * @param string $choice_markup
	 * @param array  $choice
	 * @param array  $field
	 * @param string $value
	 *
	 * @return string
	 */
	public function customize_gf_choice_other( $choice_markup, $choice, $field, $value ): string {

		if ( ! empty( $choice['isOtherChoice'] ) ) {

			$indices = array_keys( $field['choices'] );
			$index   = array_pop( $indices );

			$new_markup = sprintf(
				'<label for="choice_%1$s_%2$s_%3$s" class="gf-radio-checkbox-other-placeholder"><span class="a11y-visual-hide">%4$s</span></label></li>',
				$field['formId'],
				$field['id'],
				$index,
				__( 'Other', 'tribe' )
			);

			$choice_markup = str_replace( '</li>', $new_markup, $choice_markup );

		}

		return $choice_markup;
	}

	/**
	 * Add a custom class to the Gravity Forms select field
	 *
	 * @link   https://docs.gravityforms.com/gform_field_css_class/
	 * @filter gform_field_css_class
	 *
	 * @param string $classes
	 * @param array  $field
	 * @param array  $form
	 *
	 * @return string
	 */
	public function add_gf_select_field_class( $classes, $field, $form ): string {

		$class_icon_simple  = $this->activate_icons ? ' form-control-icon' : '';
		$class_icon_complex = $this->activate_icons ? ' form-control-icon-complex' : '';

		if ( $field['type'] === 'multiselect' || $field['inputType'] === 'multiselect' ) {
			$classes .= ' gf-multi-select';
		} elseif ( $field['type'] === 'select' || $field['inputType'] === 'select' ) {
			$classes .= ' gf-select';
			// Not Chosen, regular select
			if ( ! $field['enableEnhancedUI'] ) {
				$classes .= ' gf-select-no-chosen';
			}
		} elseif ( $field['type'] === 'checkbox' || $field['inputType'] === 'checkbox' ) {
			$classes .= ' gf-checkbox';
		} elseif ( $field['type'] === 'radio' || $field['inputType'] === 'radio' ) {
			$classes .= ' gf-radio';
		} elseif ( $field['type'] === 'textarea' || $field['type'] === 'post_content' || $field['type'] === 'post_excerpt' || $field['inputType'] === 'textarea' ) {
			$classes .= ' gf-textarea';
		} elseif ( $field['type'] === 'date' || $field['inputType'] === 'date' ) {
			$class_date_icon = ( $field['dateType'] === 'datepicker' ) ? $class_icon_simple : '';
			$classes         .= ' gf-date gf-date-layout-' . $field['dateType'] . $class_date_icon;
		} elseif ( $field['type'] === 'time' || $field['inputType'] === 'time' ) {
			$classes .= ' gf-time';
		} elseif ( $field['type'] === 'phone' || $field['inputType'] === 'phone' ) {
			$classes .= ' gf-phone' . $class_icon_simple;
		} elseif ( $field['type'] === 'name' ) {
			$classes .= ' gf-name' . $class_icon_complex;
		} elseif ( $field['type'] === 'address' ) {
			$classes .= ' gf-address' . $class_icon_complex;
		} elseif ( $field['type'] === 'email' || $field['inputType'] === 'email' ) {
			$classes .= ' gf-email' . $class_icon_simple;
		} elseif ( $field['type'] === 'website' || $field['inputType'] === 'website' ) {
			$classes .= ' gf-url' . $class_icon_simple;
		} elseif ( $field['type'] === 'fileupload' || $field['inputType'] === 'fileupload' ) {
			$classes .= ' gf-file';
		} elseif ( $field['enablePasswordInput'] === true ) {
			$classes .= ' gf-password' . $class_icon_simple;
		}

		return $classes;
	}
}
