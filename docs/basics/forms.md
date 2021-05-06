# Forms

SquareOne comes with a set of form helper classes as well as a CSS "plugin" to also handle forms powered 
by Gravity Forms, a WordPress plugin. You can find the general set of form classes along with inline documentation 
here: [`wp-content/plugins/core/assets/theme/pcss/base/forms/`](/wp-content/plugins/core/assets/theme/pcss/base/forms/).

In addition to these classes, which are ready to be implemented in your markup from the start, we have built out 
a set of simple, more general variables (`wp-content/plugins/core/assets/theme/pcss/utilities/variables/forms.pcss`) that should get 
you about 95% of the way to meeting your design. There is also a set of more granular variables to allow for 
heavier customization and fine tuning (`wp-content/plugins/core/assets/theme/pcss/utilities/variables/forms/`).

## Our Base/Vendor Approach

We've implemented an easy way to have consistency between your base form styling (i.e. site-wide search boxes, filters, other inputs)
and your Gravity Forms kitchen sink styling.

All default/base form styling is done in the [base form directory](/wp-content/plugins/core/assets/theme/pcss/base/forms/), while all
Gravity Forms-specific styling is done in the [vendor directory](wp-content/themes/core/pcss/vendor/gravity-forms/).  One of the
main reasons for splitting up our styles using this Base/Vendor approach, is so that we can more easily transition to a different
forms plugin should the need arise in the future.


## Implementing Base Forms Styling

As the name implies, the "Base" form styles apply globally to all form elements.  All of the base form styling is controlled 
using variables that are defined in `wp-content/themes/core/pcss/utilities/variables/_forms.pcss`
and `wp-content/themes/core/pcss/utilities/variables/forms`.  To implement the majority of designs, you will only need to
modify these variables.
 
### Folder Structure
 
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

### Form Mixins

As you browse the base form styling partials, you'll notice many references to mixins.  All form-related mixins can be
found here: `wp-content/themes/core/pcss/utilities/mixins/_forms.pcss`.  We make use of these in many different places to
keep our styling declarations consistent and re-usable.  If you're using custom form elements that need styling, try to use these
mixins wherever possible. 

## Implementing Gravity Forms Styling

There are many different form builder plugins WordPress.  For our purposes, we've chosen [Gravity Forms](https://www.gravityforms.com/). 

All of our Gravity Forms styling builds on top of the SquareOne base form styling, so you'll definitely want to [implement
the base form styling](/docs/frontend/forms/base.md) prior to beginning any type of Gravity Forms implementation.

As with all of our styling for third-party libraries, the Gravity Forms styles are located in the `vendor` directory here:
`wp-content/themes/core/pcss/vendor/gravity-forms`.  You'll want to pay close attention to the `_variables.pcss` partial,
since modifying those variables will typically accomplish everything you need to customize.

### Configuring Gravity Forms

First, you'll want to configure your Gravity Forms settings correctly, so you don't get any conflicting styles from the plugin.
In your WordPress instance, go to `Forms->Settings` and make sure that the "Output CSS" option is set to NO, and the "Output HTML 5"
option is set to YES ([screenshot](http://p.tri.be/utQTTC)).  After that, you'll want to import a form to use for testing purposes.  Download [this zip file](http://p.tri.be/4UacvT), 
uncompress it, and then import it by going to `Forms->Import/Export->Import Forms`.  You'll want to upload that file to create
a new form for testing.  Finally, embed that form on a new page, and you'll be ready to start styling.

### Accessibility with Gravity Forms

By default, Gravity Forms has some a11y challenges, so we're using a third-party plugin to help address these issues: [WCAG 2.0 form fields for Gravity Forms](https://wordpress.org/plugins/gravity-forms-wcag-20-form-fields/). You'll want to make sure that this plugin is installed and activated for any projects that utilize Gravity Forms.

### Folder Structure
 
* **pcss/vendor/gravity-forms**
    * **controls** - styling for GF-specific controls (i.e. buttons, select fields, radio, etc)
    * **validation** - styling for GF validation classes
    * _complex-layouts.pcss - styling for GF-specific layout classes
    * _default.pcss - default styling for GF-specific form classes
    * _gf-classes.pcss - Styling for GF-specific field layout classes
    * _label.pcss - styling for GF-specific form labels and other input helpers
    * _spinner.pcss - styling for GF form loading spinner
    * _variables.pcss - comprehensive declaration of all GF form variables
    
## Gravity Forms JavaScript

We have a simple JavaScript module for forms that handles auto-scroll and form submission located here: `wp-content/themes/core/js/src/theme/modules/forms.js`.
This should work just fine without any customizations needed, but any Gravity Forms-specific JavaScript that needs to be added
should be done in this module.

## Gravity Forms PHP

We have PHP classes that contains several filters used for customizing Gravity Forms located in
`wp-content/plugins/core/src/Integrations/Gravity_Forms`. If you ever need to add any Gravity Forms specific filters,
this is the place to do it.
