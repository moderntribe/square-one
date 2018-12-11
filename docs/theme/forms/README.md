# Forms

Square One comes with a set of form helper classes as well as a CSS "plugin" to also handle forms powered 
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

## Table of Contents

* [Implementing Base Forms Styling](/docs/theme/forms/base.md)
* [Implementing Gravity Forms Styling](/docs/theme/forms/gravity-forms.md)
