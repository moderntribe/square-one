<?php
declare( strict_types=1 );

namespace Tribe\Project\Integrations\Gravity_Forms;

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Integrations\Gravity_Forms\Choice_Other;

class Form_Markup {
	/**
	 * @var bool Used to enable/disable CSS classes that control icon placement inside some field types.
	 */
	private $activate_icons = false;
	/**
	 * @var Component_Factory
	 */
	private $component;

	public function __construct( Component_Factory $component_factory ) {
		$this->component = $component_factory;
	}

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

			$label = $this->component->get( Choice_Other::class, [
				Choice_Other::FORM_ID     => $field['formId'],
				Choice_Other::FIELD_ID    => $field['id'],
				Choice_Other::FIELD_INDEX => $index,
				Choice_Other::LABEL       => __( 'Other', 'tribe' ),
			] );

			$choice_markup = str_replace( '</li>', $label . '</li>', $choice_markup );

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
