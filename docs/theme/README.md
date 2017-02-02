# Theme Overview

Check `local-config-sample.php` for how to setup your basic wp constants when initializing the project.

The main theme for this system lives in `/wp-content/themes/core`. 

If needed base child themes off of this core. 

To begin theme work you must first have your node_modules installed and be on the correct version of node. Check the [Node](/docs/guides/node.md) and [Grunt](/docs/theme/grunt.md) readmes for details on the setup here and the development tasks you need to run.

Check the [PostCSS](/docs/theme/css/postcss.md) readme for information on the css system in place.

For information on all things Javascript, check [here](/docs/theme/js/javascript.md)

For your system setup you must define `SCRIPT_DEBUG` as true in your `local-config.php` at root. Otherwise the front end will load minified files which wont work for dev.

## Table of Contents

* Theme
  * [Overview](/docs/theme/README.md)
  * [Markup and Style](/docs/theme/markup-and-style.md)
  * [Accessibility](/docs/theme/accessbility.md)
  * [Image Handling](/docs/theme/images.md)
  * [Fonts](/docs/theme/fonts.md)
  * [Icons](/docs/theme/icons.md)
* CSS
  * [Overview](/docs/theme/css/README.md)
  * [PostCSS](/docs/theme/css/postcss.md)
  * [Grids](/docs/theme/css/grids.md)
  * [Plugins](/docs/theme/css/plugins.md)
  * [Forms](/docs/theme/css/forms.md)
* Javascript
  * [Overview](/docs/theme/js/README.md)
  * [Selectors](/docs/theme/js/selectors.md)
  * [Events](/docs/theme/js/events.md)
  * [Jquery](/docs/theme/js/jquery.md)
