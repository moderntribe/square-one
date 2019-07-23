# Implementing Gravity Forms Styling

There are many different form builder plugins WordPress.  For our purposes, we've chosen [Gravity Forms](https://www.gravityforms.com/). 

All of our Gravity Forms styling builds on top of the SquareOne base form styling, so you'll definitely want to [implement
the base form styling](/docs/frontend/forms/base.md) prior to beginning any type of Gravity Forms implementation.

As with all of our styling for third-party libraries, the Gravity Forms styles are located in the `vendor` directory here:
`wp-content/themes/core/pcss/vendor/gravity-forms`.  You'll want to pay close attention to the `_variables.pcss` partial,
since modifying those variables will typically accomplish everything you need to customize.

## Configuring Gravity Forms

First, you'll want to configure your Gravity Forms settings correctly, so you don't get any conflicting styles from the plugin.
In your WordPress instance, go to `Forms->Settings` and make sure that the "Output CSS" option is set to NO, and the "Output HTML 5"
option is set to YES ([screenshot](http://p.tri.be/utQTTC)).  After that, you'll want to import a form to use for testing purposes.  Download [this zip file](http://p.tri.be/4UacvT), 
uncompress it, and then import it by going to `Forms->Import/Export->Import Forms`.  You'll want to upload that file to create
a new form for testing.  Finally, embed that form on a new page, and you'll be ready to start styling.

## Accessibility with Gravity Forms

By default, Gravity Forms has some a11y challenges, so we're using a third-party plugin to help address these issues: [WCAG 2.0 form fields for Gravity Forms](https://wordpress.org/plugins/gravity-forms-wcag-20-form-fields/). You'll want to make sure that this plugin is installed and activated for any projects that utilize Gravity Forms.

## Folder Structure
 
 * **pcss/vendor/gravity-forms**
     * **controls** - styling for GF-specific controls (i.e. buttons, select fields, radio, etc)
     * **validation** - styling for GF validation classes
     * _complex-layouts.pcss - styling for GF-specific layout classes
     * _default.pcss - default styling for GF-specific form classes
     * _gf-classes.pcss - Styling for GF-specific field layout classes
     * _label.pcss - styling for GF-specific form labels and other input helpers
     * _spinner.pcss - styling for GF form loading spinner
     * _variables.pcss - comprehensive declaration of all GF form variables
     
## Up Next

* [Gravity Forms JavaScript](/docs/frontend/forms/javascript.md) 
