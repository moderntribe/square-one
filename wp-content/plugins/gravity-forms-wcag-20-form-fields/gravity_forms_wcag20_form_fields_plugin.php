<?php
/*
Plugin Name: WCAG 2.0 form fields for Gravity Forms
Description: Extends the Gravity Forms plugin. Modifies fields and improves validation so that forms meet WCAG 2.0 accessibility requirements.
Tags: Gravity Forms, wcag, accessibility, forms
Version: 1.4.6
Author: Adrian Gordon
Author URI: https://www.itsupportguides.com
License: GPL2
Text Domain: gravity-forms-wcag-20-form-fields

------------------------------------------------------------------------
Copyright 2015 Adrian Gordon

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

load_plugin_textdomain( 'gravity-forms-wcag-20-form-fields', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

add_action( 'admin_notices', array( 'ITSP_GF_WCAG20_Form_Fields', 'admin_warnings' ), 20 );

if ( !class_exists( 'ITSP_GF_WCAG20_Form_Fields' ) ) {
    class ITSP_GF_WCAG20_Form_Fields
    {
		private static $name = 'WCAG 2.0 form fields for Gravity Forms';
		private static $slug = 'gravity-forms-wcag-20-form-fields';

        /**
         * Construct the plugin object
         */
		 public function __construct() {
			// register plugin functions through 'gform_loaded' -
			// this delays the registration until all plugins have been loaded, ensuring it does not run before Gravity Forms is available.
            add_action( 'gform_loaded', array( &$this, 'register_actions' ) );

        } // END __construct

		/**
         * Register plugin functions
         */
		function register_actions() {
            if ( ( self::is_gravityforms_installed() ) ) {
				//start plug in
				add_filter( 'gform_column_input_content',  array( &$this, 'change_column_add_title_wcag' ), 10, 6 );
				add_filter( 'gform_field_content',  array( &$this, 'change_fields_content_wcag' ), 10, 5 );
				add_action( 'gform_enqueue_scripts',  array( &$this, 'queue_scripts' ), 90, 3 );
				add_filter( 'gform_tabindex', create_function( '', 'return false;' ) );   //disable tab-index
				add_filter( 'gform_validation_message', array( &$this, 'change_validation_message' ), 10, 2 );

				//add_filter('gform_pre_render', array(&$this,'set_save_continue_button')); // TO DO: currently customising Gravity Forms code, need to implement in this plugin.
			}
		 } // END register_actions

		/**
         * Replaces default 'Save and continue' link with a button
		 *  - not currently used
         */
		function set_save_continue_button( $form ) {
			$form['save']['button']['type'] = 'text';
			return $form;
		} // END set_save_continue_button

		public static function change_validation_message( $message, $form ) {
			$referrer = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $_SERVER["REQUEST_URI"];
			$error = '';
			$message = ''; // clear $messages so default GF error message is not displayed (There was a problem with your submission. Errors have been highlighted below.)
			foreach ( $form['fields'] as $field ) {
				$failed[] = rgget( 'failed_validation', $field );
				$failed_field = rgget( 'failed_validation', $field );
				$failed_message = strip_tags( rgget( 'validation_message', $field ) );
				if ( 1 == $failed_field ) {
					$error .= '<li><a href="' . $referrer . '#field_' . $form['id'] . '_' . $field['id'] .'">' . $field['label'] . ' - ' . ( ( "" == $field['errorMessage'] ) ? $failed_message:$field['errorMessage'] ) . '</a></li>';
				}
			}

			$length  = count( array_keys( $failed, "true" ));
			$prompt  = sprintf( _n( 'There was %s error found in the information you submitted', 'There were %s errors found in the information you submitted', $length, 'gravity-forms-wcag-20-form-fields' ), $length );

			add_action( 'wp_footer', array( 'ITSP_GF_WCAG20_Form_Fields', 'change_validation_message_js_script' ), 999 );

			$message .= "<div id='error' aria-live='assertive' role='alert'>";
			$message .= "<div class='validation_error' ";
			if( !has_action( 'itsg_gf_wcag_disable_tabindex' ) ) {
				$message .= "tabindex='-1'";
			}
			$message .= ">";
			$message .= $prompt;
			$message .= "</div>";
			$message .= "<ol class='validation_list'>";
			$message .= $error;
			$message .= "</ol>";
			$message .= "</div>";
			return $message;
		} // END change_validation_message

		public static function change_validation_message_js_script() {
		?>
			<script>
				(function ($) {
					'use strict';
					$(function () {
						//$(document).bind('gform_post_render', function(){
						//	window.setTimeout(function(){
								window.location.hash = '#error';
								$( this ).find( '.validation_error' ).focus();
								$( this ).scrollTop( $( '.validation_error' ).offset().top );
							}, 500);
						//});
					//});
				}(jQuery));
				</script>
		<?php
		} // END change_validation_message_js_script

		/**
         * Replaces field content for repeater lists 
		 * - adds title to input fields using the column title
         */
		public static function change_column_add_title_wcag( $input, $input_info, $field, $text, $value, $form_id ) {
			if ( !is_admin() && 'print-entry' != RGForms::get( 'gf_page' ) ) {
				$input = str_replace( "<input ", "<input title='" . $text . "'", $input );
			}
		return $input;
		} // END change_column_add_title_wcag

		/**
         * Main function
		 * - Replaces field content with WCAG 2.0 compliant HTML
         */
		public static function change_fields_content_wcag( $content, $field, $value, $lead_id, $form_id ) {
			if ( !is_admin() && 'print-entry' != RGForms::get( 'gf_page' ) ) {
			$field_type = rgar( $field, 'type' );
			$field_required = rgar( $field, 'isRequired' );
			$field_failed_valid = rgar( $field, 'failed_validation' );
			$field_label = htmlspecialchars( rgar( $field, 'label' ), ENT_QUOTES );
			$field_id = rgar( $field, 'id' );
			$field_page = rgar( $field, 'pageNumber' );
			$current_page = GFFormDisplay::get_current_page( $form_id );
			$field_description = rgar( $field, 'description' );
			$field_maxFileSize = rgar( $field, 'maxFileSize' );
			$field_allowedExtensions = rgar( $field, 'allowedExtensions' );

			// wrap single fileupload file field in fieldset
			// adds aria-required='true' if required field
			if ( 'fileupload' == $field_type ) {
					if ( true == $field_required ) {
						$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'><label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . "<span class='gfield_required'>*</span><span class='sr-only'> " .__( 'File upload', 'gravity-forms-wcag-20-form-fields' ) . " </span></label></legend>", $content );
					} else {
						$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'><label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . "<span class='sr-only'> " . __( 'File upload', 'gravity-forms-wcag-20-form-fields' ) . " </span></label></legend>", $content );
					}
					$content .= "</fieldset>";
			}

			// wrap radio and checkbox fields in fieldset
			// adds aria-required='true' if required field
			elseif ( 'checkbox' == $field_type || 'radio' == $field_type || 'option' == $field_type ) {
				// adds labels to radio 'other' field - both the radio and input fields.
				if( 'radio' == $field_type ) {
					foreach( $field['choices'] as $key=>$choice ) {
						$isotherchoice = isset( $choice['isOtherChoice'] ) ? $choice['isOtherChoice'] : null;
						if ( true == $isotherchoice ) {
							$choice_position = $key;
							// add label to radio
							$content = str_replace( "<li class='gchoice_" . $form_id . "_" . $field_id . "_" . $choice_position . "'><input name='input_" . $field_id . "' ", "<li class='gchoice_" . $form_id . "_" . $field_id . "_" . $choice_position . "'><label id='label_" . $form_id . "_" . $field_id . "_" . $choice_position . "' for='choice_" . $form_id . "_" . $field_id . "_" . $choice_position . "' class='sr-only'>" . __( 'Other', 'gravity-forms-wcag-20-form-fields' )." </label><input name='input_" . $field_id . "' ", $content );
							// add label to text input
							$content = str_replace( "<input id='input_" . $form_id . "_" . $field_id . "_other' ", "<label id='label_" . $form_id . "_" . $field_id . "_other' for='input_" . $form_id . "_" . $field_id . "_other' class='sr-only'>" . __( 'Other', 'gravity-forms-wcag-20-form-fields' ) . " </label><input id='input_" . $form_id . "_" . $field_id . "_other' ", $content );
							// change radio jQuery
							//$content = str_replace( "jQuery(this).next('input').focus()","jQuery(this).closest('li').find('#input_43_1_other').focus()", $content );
							// change inout jQuery - NOTE Gravity Forms code uses double quotation mark
							//$content = str_replace( "jQuery(this).prev(\"input\").attr(\"checked\", true)","jQuery(this).closest(\"li\").find(\"#choice_43_1_3\").attr(\"checked\", true)", $content );
						}
					}
				}
				//wrap in fieldset
				if ( true == $field_required ) {
					// Gravity Forms 1.9.2 appears to no longer include for attribute on field group labels
					// for='input_" . $form_id . "_" . $field_id . "'
					$content = str_replace( "<label class='gfield_label'  >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
					// or if version of GF doesnt have the double empty space
					$content = str_replace( "<label class='gfield_label' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
				} else {
					$content = str_replace( "<label class='gfield_label'  >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
					// or if version of GF doesnt have the double empty space
					$content = str_replace( "<label class='gfield_label' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
				}
				$content .= "</fieldset>";
			}

			// name field in fieldset
			// adds aria-required='true' if required field
			elseif ( 'name' == $field_type ) {
			// wrap in fieldset
			// includes variations for 2-8 depending on field configuration
				if ( true == $field_required ) {
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_2' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_3' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_4' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_6' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_8' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
				} else {
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_2' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_3' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_4' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>",$content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_6' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_8' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
				}
				$content .= "</fieldset>";
			}

			// email field in fieldset
			// adds aria-required='true' if required field
			elseif ( 'email' == $field_type && true == rgar( $field, 'emailConfirmEnabled') ){
			//wrap in fieldset
				if ( true == $field_required ) {
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
				} else {
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
				}
				$content .= "</fieldset>";
			}

			// address field in fieldset
			// adds aria-required='true' if required field
			elseif ( 'address' == $field_type ) {
			//wrap in fieldset
				if ( true == $field_required ) {
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_1' >" . $field_label . "<span class='gfield_required'>*</span></label>", "<fieldset aria-required='true' class='gfieldset'><legend class='gfield_label'>" . $field_label . "<span class='gfield_required'>*</span></legend>", $content );
				} else {
					$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_1' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
				}
				$content .= "</fieldset>";
			}

			elseif ( 'list' == $field_type ) {
				$maxRow = intval( rgar( $field, 'maxRows' ) );

				//wrap list fields in fieldset
				$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "_shim' >" . $field_label . "</label>", "<fieldset class='gfieldset'><legend class='gfield_label'>" . $field_label . "</legend>", $content );
				$content .= "</fieldset>";

				//remove shim input
				$content = str_replace( "<input type='text' id='input_" . $form_id . "_" . $field_id . "_shim' style='position:absolute;left:-999em;' onfocus='jQuery( \"#field_" . $form_id."_" . $field_id . " table tr td:first-child input\" ).focus();' />", "", $content );

				//replace 'add another row' image with button
				$add_row = _x( 'Add a row', 'String must have same translation as found in Gravity Forms', 'gravity-forms-wcag-20-form-fields' );
				$content = str_replace( "<img src='" . GFCommon::get_base_url() . "/images/blankspace.png' class='add_list_item '  title='{$add_row}' alt='{$add_row}' onclick='gformAddListItem(this, " . $maxRow . ")' style='cursor:pointer; margin:0 3px;' />", "<button type='button' class='add_list_item'  title='{$add_row}' alt='{$add_row}' onclick='gformAddListItem(this, " . $maxRow . ")'></button>", $content );

				//replace 'remove this row' image with button - if field is visible
				// removew row
				$remove_row = _x( 'Remove this row', 'String must have same translation as found in Gravity Forms', 'gravity-forms-wcag-20-form-fields' );
				$content = str_replace( "<img src='" . GFCommon::get_base_url() . "/images/blankspace.png'  title='{$remove_row}' alt='{$remove_row}' class='delete_list_item' style='cursor:pointer; ' onclick='gformDeleteListItem(this, " . $maxRow . ")' />", "<button type='button' class='delete_list_item' title='{$remove_row}' alt='{$remove_row}' onclick='gformDeleteListItem(this, " . $maxRow . ")'></button>", $content );

				//replace 'remove this row' image with button - if field is hidden
				$content = str_replace( "<img src='" . GFCommon::get_base_url() . "/images/blankspace.png'  title='{$remove_row}' alt='{$remove_row}' class='delete_list_item' style='cursor:pointer; visibility:hidden;' onclick='gformDeleteListItem(this, " . $maxRow . ")' />", "<button style='visibility:hidden;' type='button' class='delete_list_item'  title='{$remove_row}' alt='{$remove_row}' onclick='gformDeleteListItem(this, " . $maxRow . ")'></button>", $content );
			}

			// add description for date field
			elseif ( 'date' == $field_type ) {
				if ( 'dmy' == $field['dateFormat'] ) {
					$date_format = 'dd/mm/yyyy';
				} elseif ( 'dmy_dash' == $field['dateFormat'] ) {
					$date_format = 'dd-mm-yyyy';
				} elseif ( 'dmy_dot' == $field['dateFormat'] ) {
					$date_format = 'dd.mm.yyyy';
				} elseif ( 'ymd_slash' == $field['dateFormat'] ) {
					$date_format = 'yyyy/mm/dd';
				} elseif ( 'ymd_dash' == $field['dateFormat'] ) {
					$date_format = 'yyyy-mm-dd';
				} elseif ( 'ymd_dot' == $field['dateFormat'] ) {
					$date_format = 'yyyy.mm.dd';
				} else {
					$date_format = 'mm/dd/yyyy';
				} 

				$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label, "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . " <span id='field_" . $form_id . "_" . $field_id . "_dmessage' class='sr-only'> - " . sprintf( __( 'must be %s format', 'gravity-forms-wcag-20-form-fields' ), $date_format ) . "</span>", $content );

				// attach to aria-described-by
				$content = str_replace( " name='input_", " aria-describedby='field_" . $form_id . "_" . $field_id . "_dmessage' name='input_", $content);
			}

			// add description for website field
			elseif ( 'website' == $field_type ){
				$content = str_replace( "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label, "<label class='gfield_label' for='input_" . $form_id . "_" . $field_id . "' >" . $field_label . " <span id='field_" . $form_id . "_" . $field_id . "_dmessage' class='sr-only'> - " . __( 'enter a valid website URL for example http://www.google.com', 'gravity-forms-wcag-20-form-fields' ) . "</span>", $content );

				// attach to aria-described-by
				$content = str_replace( " name='input_", " aria-describedby='field_" . $form_id . "_" . $field_id . "_dmessage' name='input_", $content );
			}

			//validation for fields in page
			if ( $current_page == $field_page ) {
				//if field has failed validation
					if( true == $field_failed_valid ){
					//add add aria-invalid='true' attribute to input
					$content = str_replace( " name='input_", " aria-invalid='true' name='input_", $content);
					//if aria-describedby attribute not already present
					if ( false !== strpos( strtolower( $content ), 'aria-describedby') )  {
						$content = str_replace( " aria-describedby='", " aria-describedby='field_" . $form_id."_" . $field_id . "_vmessage ", $content);
					} else {
						// aria-describedby attribute is already present
						$content = str_replace( " name='input_", " aria-describedby='field_" . $form_id . "_" . $field_id . "_vmessage' name='input_", $content );
					}
					//add add class for aria-describedby error message
					$content = str_replace( " class='gfield_description validation_message'", " class='gfield_description validation_message' id='field_" . $form_id . "_" . $field_id . "_vmessage'", $content );
				}

				//if field is required
				if( true == $field_required ) {
					//if HTML required attribute not already present
					// COMMENTED OUT in version 1.2.6 until I can resolve issues are resolved with it prompting 'required' when field has been filled out correctly.
					// aria-required=true still working and seems to provide broader support for assistive technology
					/*	if (( strpos(strtolower($content),'required') !== true ) && ("checkbox" != $field_type ) )  {
						//add HTML5 required attribute
						$content = str_replace( " name='input_"," required name='input_", $content );
					} */
					if ( ( true !== strpos( strtolower( $content ), "aria-required='true'" ) ) && ( 'checkbox' != $field_type ) && ( 'radio' != $field_type ) ) {
						//add aria-required='true'
						$content = str_replace( " name='input_"," aria-required='true' name='input_", $content );
					}
					//add screen reader only 'Required' message to asterisk
					$content = str_replace( "*</span>", " * <span class='sr-only'> " . __( 'Required', 'gravity-forms-wcag-20-form-fields' ) . "</span></span>", $content );
				}

				if( !empty( $field_description ) && 'Infobox' != $field_type ) {
				// if field has a description, link description to field using aria-describedby
					// dont apply to validation message - it already has an ID
					//if (strpos(strtolower($content),'_vmessage') !== true)  {
						//if aria-describedby attribute not already present
						if ( false !== strpos( strtolower( $content ), 'aria-describedby' ) )  {
							$content = str_replace( " aria-describedby='", " aria-describedby='field_" . $form_id . "_" . $field_id . "_dmessage ", $content);
						} else {
							// aria-describedby attribute is already present
							$content = str_replace( " name='input_", " aria-describedby='field_" . $form_id . "_" . $field_id . "_dmessage' name='input_", $content);
						}
						//add add class for aria-describedby description message
						$content = str_replace( " class='gfield_description'", " id='field_" . $form_id . "_" . $field_id . "_dmessage' class='gfield_description'", $content );
					//}
				}

				if ( 'fileupload' == $field_type ) {
					if( ! empty( $field_maxFileSize ) ) {
						// turn max file size to human understandable term
						$file_limit = $field_maxFileSize. ' mega bytes';
					}
					if ( !empty( $field_allowedExtensions ) ) {
						// add accept attribute with comma separated list of accept file types
						$content = str_replace( " type='file' ", " type='file' accept='" . $field_allowedExtensions . "'", $content);
						// turn allowed extensions into a human understandable list - remove commas and replace with spaces
						$extensions_list = str_replace( ",", " ", $field_allowedExtensions );
					}

					// only add if either max file size of extension limit specified for field
					if ( !empty( $field_maxFileSize ) || !empty( $field_allowedExtensions ) ) {
						//add title attirbute to file input field
							$content = str_replace( " type='file' ", " type='file' title='" . $field_label . "' ", $content );
						//if aria-describedby attribute not already present
						if ( false !== strpos( strtolower( $content ), 'aria-describedby' ) )  {
							$content = str_replace( " aria-describedby='", " aria-describedby='field_" . $form_id . "_" . $field_id . "_fmessage ", $content );
						} else {
						// aria-describedby attribute is already present
							$content = str_replace( " name='input_", " aria-describedby='field_" . $form_id . "_" . $field_id . "_fmessage' name='input_", $content );
						}
						$content .= "<span id='field_" . $form_id . "_" . $field_id . "_fmessage' class='sr-only'>";
						if( !empty( $field_maxFileSize ) ) {
							$content .= __( 'Maximum file size', 'gravity-forms-wcag-20-form-fields' ) . ' - ' . $file_limit . '. ';
						}
						if( !empty( $field_allowedExtensions ) ) {
							$content .= __( 'Allowed file extensions', 'gravity-forms-wcag-20-form-fields' ) . ' - ' . $extensions_list . ". ";
						}
						$content .= "</span>";
					}
				}
			}

		}
		return $content;
		} // END change_fields_content_wcag

		/*
         * Enqueue styles and scripts.
         */
		public static function queue_scripts( $form, $is_ajax ) {
			if ( !is_admin() ) {
				//add_action( 'wp_enqueue_scripts', array( &$this,'css_styles' ) );
				wp_enqueue_style( 'gravity-forms-wcag-20-form-fields-css', plugins_url( 'css/gf_wcag20_form_fields.css', __FILE__ ) );
				
				/*
				 * Looks for links in form body (in descriptions, HTML fields etc.)
				 * changes them to open in a new window and adds/appends
				 * 'this link will open in a new window' to title for screen reader users.
				 */
				wp_register_script( 'gf_wcag20_form_fields_js', plugins_url( '/js/gf_wcag20_form_fields.js', __FILE__ ),  array( 'jquery' ) );

				$settings_array = array(
					'new_window_text' => esc_js( __( 'this link will open in a new window', 'gravity-forms-wcag-20-form-fields' ) )
				);
				
				wp_localize_script( 'gf_wcag20_form_fields_js', 'gf_wcag20_form_fields_settings', $settings_array );

				// Enqueued script with localized data.
				wp_enqueue_script( 'gf_wcag20_form_fields_js' );

			}
		}  // END queue_scripts

		/*
         * CSS styles - remove border, margin and padding from fieldset
         */
		public static function css_styles() {
			wp_enqueue_style( 'gravity-forms-wcag-20-form-fields-css', plugins_url( 'gf_wcag20_form_fields.css', __FILE__ ) );
		} // END css_styles

		/*
         * Warning message if Gravity Forms is not installed and enabled
         */
		public static function admin_warnings() {
			if ( !self::is_gravityforms_installed() ) {
				$gravityforms_url = '<a href="https://www.e-junkie.com/ecom/gb.php?cl=54585&c=ib&aff=299380" target="_blank" >' . __( 'download the latest version', 'gravity-forms-wcag-20-form-fields' ) . '</a>';

				printf(
					'<div class="error"><h3>%s</h3><p>%s</p><p>%s</p></div>',
						__( 'Warning', 'gravity-forms-wcag-20-form-fields' ),
						sprintf ( __( 'The plugin %s requires Gravity Forms to be installed.', 'gravity-forms-wcag-20-form-fields' ), '<strong>'.self::$name.'</strong>' ),
						sprintf ( __( 'Please %s of Gravity Forms and try again.', 'gravity-forms-wcag-20-form-fields' ), $gravityforms_url )
				);
			}
		} // END admin_warnings

		/*
         * Check if GF is installed
         */
        private static function is_gravityforms_installed() {
			if ( !function_exists( 'is_plugin_active' ) || !function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}
			if ( is_multisite() ) {
				return ( is_plugin_active_for_network( 'gravityforms/gravityforms.php' ) || is_plugin_active( 'gravityforms/gravityforms.php' ) );
			} else {
				return is_plugin_active( 'gravityforms/gravityforms.php' );
			}
        } // END is_gravityforms_installed
	}
    $ITSP_GF_WCAG20_Form_Fields = new ITSP_GF_WCAG20_Form_Fields();
}