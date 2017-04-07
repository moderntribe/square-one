=== WCAG 2.0 form fields for Gravity Forms ===
Contributors: ovann86
Donate link: http://www.itsupportguides.com/
Tags: gravity forms, wcag, accessibility, usability
Requires at least: 4.1
Tested up to: 4.7.0
Stable tag: 1.5.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Modifies Gravity Forms form fields and improves validation so that forms meet WCAG 2.0 accessibility requirements.

== Description ==

> This plugin is an add-on for the <a href="https://www.e-junkie.com/ecom/gb.php?cl=54585&c=ib&aff=299380" target="_blank">Gravity Forms</a> (affiliate link) plugin. If you don't yet own a license for Gravity Forms - <a href="https://www.e-junkie.com/ecom/gb.php?cl=54585&c=ib&aff=299380" target="_blank">buy one now</a>! (affiliate link)

**What does this plugin do?**

* Wraps radio, checkbox and list (repeater) fields in a fieldset.
* Improves form validation by displaying an on-page message that describes how many errors there were in the page. The message contains a list of the form fields with the errors, a description of the error and a link to the field.
* Adds aria-describedby attributes for date and website fields - providing clear instructions for screen reader users of what format is required for the field.
* Adds aria-required='true' for required fields
* Adds aria-describedby attributes for fields that have failed validation - providing clear instructions for screen reader users of what the field error is. Description used is the default validation message for the field, or if set, the validation message for the field.
* Disables the Gravity Forms configured tabindex - this stops users from being able to tab between fields and on-page links.
* Changes links in the form body, such as field descriptions or HTML fields, so they open in a new window. A title is added or appended to any existing title for screen reader users which reads 'this link will open in a new window'.
* Improved file upload field - wrapped in field set, clearly identifies to screen reader users if any file size of file type restrictions have been set of the field.
* Improved field instructions - if a description has been provided for the field, the field is 'described by' the description, using the aria-describedby attribute

> See a demo of this plugin at [demo.itsupportguides.com/gravity-forms-wcag-20-form-fields](http://demo.itsupportguides.com/gravity-forms-wcag-20-form-fields/ "demo website")

**How to I use the plugin?**

Simply install and activate the plugin - no configuration required.

**Have a suggestion, comment or request?**

Please leave a detailed message on the support tab. 

**Let me know what you think**

Please take the time to review the plugin. Your feedback shows the need for Gravity Forms to meet the WCAG 2.0 requirements natively, and will help me understand the value of this plugin.

**Please note:**

* Accessibility is a complicated topic and sometimes there are different opinions on how to best achieve an accessible website. Accessible forms are even harder to achieve, with many different approaches. If you have a suggestion, comment or request please leave a detailed message on the support tab.
* This plugin does not cover other aspects of accessibility, such as content order, clear instructions, colour contrast etc.
* You will need to ensure that your websites theme is accessible. 

**Disclaimer**

*Gravity Forms is a trademark of Rocketgenius, Inc.*

*This plugins is provided “as is” without warranty of any kind, expressed or implied. The author shall not be liable for any damages, including but not limited to, direct, indirect, special, incidental or consequential damages or losses that occur out of the use or inability to use the plugin.*

== Installation ==

1. This plugin requires the Gravity Forms plugin, installed and activated
2. Install plugin from WordPress administration or upload folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in the WordPress administration
4. The radio, checkbox and repeater list fields will now be automatically modified so that they meet the accessibility requirements.

== Frequently Asked Questions ==

**I still see errors on my form**

Whilst this plugin goes a long way to taking a Gravity Form towards WCAG 2.0 compliance, there are still some things that haven't been looked at yet.

If you're having troubles or even better know a solution, please leave a detailed message on the support tab.

I am aware of three issues that are yet to be resolved - duplicate ID's for checkbox lists, duplicate ID's for multi-page form wrappers (the hidden pages have the same ID), and duplicate ID's for the 'Save and continue later' link/button.

**Opening links in new windows - isn't that bad practice?**

Typically forcing links to open in a new window is bad practice, both from a usability and accessibility point of view. However when it comes to forms there is reason enough to do this - if the user clicks on the link they are taken away from the form - loosing any data they may have provided.

This plugin uses jQuery to modify the form once the browser has loaded it, any links in the form are changed to open in a new window (target='_blank'), then a title is added (or appended to the existing title) which reads 'this link will open in a new window'.

This is the	[H33: Supplementing link text with the title attribute](http://www.w3.org/TR/WCAG20-TECHS/H33.html) technique.

Why not use the [C7: Using CSS to hide a portion of the link text](http://www.w3.org/TR/2014/NOTE-WCAG20-TECHS-20140916/C7) technique?

I'm concerned it would have a negative consequence on SEO, because:

1. Search engines may down-rate your website, thinking you're attempting the black hat practice of stuffing a page with keywords that may not have any relevance to the content. 
2. Search engines may index the links with the hidden text. For example, 'document title this link will open in a new window' instead of 'document title'.

I'm willing to be convinced otherwise. But my goal is to make a Gravity Form accessible for everyone - which needs to take into account how it affects search engines.

**How is the plugin tested**

The plugin is tested using screen-reader software JAWS and the latest version of Internet Explorer, Firefox and Chrome.

The plugin is developed using the latest version of Gravity Forms. The plugin may work in previous versions but could have mixed results. Only the latest version of Gravity Forms can be supported.

The aim of the plugin is to make forms created using Gravity Forms have valid HTML and comply with WCAG 2.0 - level AA.

Each change the plugin makes to a Gravity Form has been decided through research into best practice for usability and accessibility.

**How do I disable the tabindex on the validation message**

I DO NOT recommend removing the tabindex.

After considered research I am of the belief that tabindex with a value of =-1 is perfectly fine, and infact quite helpful in making critical messages such as a validation message appear immidately for screen reader users.

With that said, if you want to disable it you can add this code to your theme's functions.php file, below the starting <?php line.

`add_action('itsg_gf_wcag_disable_tabindex', function () {
	return true;
});`

== Screenshots ==

1. Shows the improved validation message that is displayed when the form page contains errors. Validation message describes how many errors there were on the page and a list of the fields and errors. Each error is a link to the field. This message gets the browsers 'focus' when it is loaded - allowing screen reader users easy access to the information.
2. Shows list field with 'buttons' instead of images to add and delete rows - buttons are styled like the previous images but are keyboard accessible. 

== Changelog ==

= 1.5.1 =
* Fix: resolve issue with radio field 'other' option displaying inconsistently.
* Fix: resolve issue with validation (error) message not correctly display the number and list of failed fields.

= 1.5.0 =
* Maintenance: Updates for Gravity Forms 2+
* Maintenance: Move JavaScript from footer to separate file
* Maintenance: Add minified copies of JavaScript and CSS files

= 1.4.6 =
* Fix: Add support for left and right label placement
* Maintenance: Move JavaScript out of page footer to its own file.
* Maintenance: Move CSS file to \css\ plugin directory.

= 1.4.5 =
* Maintenance: Updates to better support translation.
* Maintenance: General tidy up of code, working towards WordPress standards.

= 1.4.4 =
* Fix: Resolve issue with radio fields not being wrapped in a fieldset.
* Fix: Remove default Gravity Forms validation message from appearing ('There was a problem with your submission. Errors have been highlighted below.').
* Fix: Resolve issue with field HTML changes not applying when the label contains an HTML encodable character, e.g. a quote (').
* Fix: Resolve issue with list of linked errors in validation message when an error message contained a link. 
* Maintenance: Enqueue JavaScript later to help ensure it does not load before jQuery has loaded.

= 1.4.3 =
* Fix: Resolve issue with loading entry 'Print' page.
* Fix: Resolve '$_SERVER['HTTP_REFERER'] undefined' error message which appeared in limit circumstances.
* Maintenance: Changed several 'else' statements to 'elseif' to provide a very slight improvement with the PHP execution time.
* Maintenance: Pass translatable text in JavaScript function through json_encode() to prevent potential security issues through a XSS (cross site scripting) exploit. This is a security precaution.
* Maintenance: Added blank index.php file to plugin directory to ensure directory browsing does not occur. This is a security precaution.
* Maintenance: Added ABSPATH check to plugin PHP files to ensure PHP files cannot be accessed directly. This is a security precaution.

= 1.4.2 =
* Fix: Resolve issue with 'Save and Continue Later' link opening a new blank window.

= 1.4.1 =
* Maintenance: Refining code responsible for wrapping the name field in a fieldset.

= 1.4.0 =
* Fix: Resolve additional close fieldset </fieldset> on single file upload fields.
* Fix: Resolve issue with aria-described by applying on date fields.
* Feature: Name field now wrapped in a fieldset.
* Feature: Email field now wrapped in a fielset when 'Enable Email Confirmation' enabled.
* Feature: Address field now wrapped in a fieldset.
* Feature: Added action hook to disable tabindex=-1 on validation error message.

= 1.3.0 =
* FEATURE: Add support for multisite WordPress installations.

= 1.2.11 =
* Maintenance: change plugin name from 'Gravity Forms - WCAG 2.0 form fields' to 'WCAG 2.0 form fields for Gravity Forms'
* Maintenance: change constructor so plugin load is delayed using the 'plugins_loaded' action - this ensures the plugin loads after Gravity Forms has loaded and functions correctly.
* Maintenance: resolve various PHP errors that were appearing in debug mode, but did not affect functionality.

= 1.2.10 =

* Fix: change field layout for radio and checkboxes to allow a checkbox/radio field to not use a fieldset if only one option exists.

= 1.2.9 =

* Fix: resolve undeclared variable issue in fieldset conditions (change $fieldtype to $field_type).
* Fix: resolve issue with radio fields incorrectly being marked as required - each item in the radio field was being marked as required (using aria-required=true) when only the fieldset should have been. Reference: http://stackoverflow.com/questions/8509481/aria-required-in-a-radio-group= 1.2.8 
* Fix: resolve issue with radio field 'other' option not including a label. Added screen-reader only labels for radio and input field assigned to 'other' option.
* Fix: resolve issue with jQuery script loading before jQuery was available. Moved to footer using wp_footer action.

= 1.2.8 =

* Feature: add condition to exclude links with a class of 'target-self' from opening in a new window.

= 1.2.7 =

* Fix: fixed error in code for date field - resulting in on screen PHP error messages or the field not appearing.

= 1.2.6 =

* Feature: field description now included in a fields 'aria-describedby' attribute - giving screen reader users easy access to the fields description when jumping through fields and skipping page content.
* Feature: wrap single file upload field in a field set - providing screen reader users with the label of the upload field - instead of hearing 'browse' they will hear the title of the field followed by 'file upload'
* Feature: add 'accept' attribute to single file upload field, providing screen reader users a list of accepted file types when they select the file upload fields
* Feature: add screen reader only text below single file upload fields, providing screen readers users a human understandable description of the file type and file size restrictions placed on the field (if specified for the field)
* Maintenance: removed HTML 'required' attribute that was being applied by plugin - this was causing issues. Will be restored once this has been resolved. aria-required still applied to required fields, which is widely supported by assistive technologies.

= 1.2.5 =

* ** REMOVED ** Feature: change 'Save and continue' link to a button. This provides better accessibility by providing 'Save and continue' as a form field - making it listed along side with the 'Previous', 'Next' and 'Submit' buttons when a screen reader user lists all form fields. e.g. JAWS + F5.

= 1.2.4 =

* Fix: required checkbox and radio fields missing 'required' asterisk since version 1.2.2.

= 1.2.3 =

* Feature: links in form body, such as field descriptions or HTML fields, will be made to open in a new window. A title is added or appended to any existing title for screen reader users which reads 'this link will open in a new window'.


= 1.2.2 =

* Enqueue stylesheet instead of directly printing to page.
* Replace i18n slug with slug string instead of class reference.
* Fix text strings for internationalization.
* Fix bug with failing to be inserted.
* Add ARIA live attribute to form validation error
* Remove JS alert to avoid redundant notifications with ARIA

= 1.2.1 =

* Fix: added condition so that 'required' attributes are only added for fields on current page. 

= 1.2.0 =

* Fix: changed links in validation message to be relative to the current page - allowing the links to work regardless of where the form is being loaded
* Fix: changed validation alert so that HTML 'new line' <br/> is replaced with JavaScript 'new line' /n 
* Fix: added condition to column and field changing functions so that they only function on the front end - not in the Gravity Forms forms builder
* Maintenance: improved how date format instructions are built.
* Feature: 'list' field image buttons (add new row, delete row) are not keyboard accessible. Added to to replace with actual buttons they are keyboard accessible.
* Feature: add aria-describedby for website field - 'enter a valid website URL for example http://www.google.com'
* Fix: un-did 'required' attribute for checkbox field - it unduly made ALL checkboxes required, rather than just one.
* Maintenance: moved CSS to its own file.

= 1.1.0 =

* Feature: added aria-describedby for date fields - providing screen reader users with the instructions on how to type into the field, for example 'must be dd/mm/yyyy format'
* Feature: added screen reader only words for required fields - providing screen reader users with the word 'required' in addition to the default star
* Feature: added aria-describedby for fields that have failed validation - making it easier for screen reader users to determine why a field has failed validation
* Feature: improved validation message. Message now reads 'There were 2 errors found in the information you submitted.' and is followed by a list of each field that did not pass validation. Each item in the list is a clickable link, taking the user directly to the field.
* Feature: added browser alert if form did not pass validation. If the form did not pass validation, the first thing the user should see or hear is ''There were 2 errors found in the information you submitted.' followed by the list of errors. When the user clicks past the alert the browsers focus is taken to the on screen validation message and links to errors. 

= 1.0.3 =

* Maintenance: fix php closing tag to resolve version number not appearing in WordPress Plugin Directory.

= 1.0 =

* First public release.