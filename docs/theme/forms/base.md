# Implementing Base Forms Styling

 As the name implies, the "Base" form styles apply globally to all form elements.  All of the base form styling is controlled 
using variables that are defined in `wp-content/themes/core/pcss/utilities/variables/_forms.pcss`
 and `wp-content/themes/core/pcss/utilities/variables/forms`.  To implement the majority of designs, you will only need to
 modify these variables.
 
 ## Folder Structure
 
 The folder structure for the base styling, maps to a corresponding variable file.  Example: `wp-content/themes/core/pcss/base/forms/_label.pcss` 
 maps to `wp-content/themes/core/pcss/utilities/variables/forms/_label.pcss`.  So, if you needed to modify the form label styling,
 you would simply modify the variables in the corresponding file.  If any more specific customizations needed to be made,
 then they could be made in the PCSS partial and make corresponding variables if needed.
 
 * **pcss/base/forms**
     * **controls** - styling for various form controls (i.e. buttons, select fields, radio, etc)
     * **validation** - styling for required fields
     * _attributes.pcss - styling for input placeholders
     * _default.pcss - global `<form>` styling and other wrappers for various form controls
     * _fieldset.pcss - `<fieldset>` styling
     * _label.pcss - styling for form labels and other input helpers
     * _legend.pcss - `<legend>` styling

## Form Mixins

As you browse the base form styling partials, you'll notice many references to mixins.  All form-related mixins can be
found here: `wp-content/themes/core/pcss/utilities/mixins/_forms.pcss`.  We make use of these in many different places to
keep our styling declarations consistent and re-usable.  If you're using custom form elements that need styling, try to use these
mixins wherever possible. 

## Up Next

* [Implementing Gravity Forms Styling](/docs/theme/forms/gravity-forms.md)