# CSS "Plugins"

Square One is ready to go with several CSS "plugins", which are essentially directories that contain an 
independent set of variables, styles, and essentially skins for certain plugins and functionality that we 
tend to reuse and see from project to project. You will find them at `wp-content/plugins/core/assets/theme/pcss/vendor/`.

We have them for:

* Gravity Forms
* Chosen.js
* jQuery Datepicker (primarily to be used alongside Gravity Form fields)
* Slick.js

By default they are all "active" or imported into our theme's CSS. If you do not require them for your 
project, they are implemented in a way that allows you to comment them out or delete them altogether.

TODO: potentially add documentation to go over lazy embeds and such

## Table of Contents

* [PostCSS](/docs/theme/css/postcss.md)
* [Grids](/docs/theme/css/grids.md)
* [Plugins](/docs/theme/css/plugins.md)
* [Forms](/docs/theme/css/forms.md)