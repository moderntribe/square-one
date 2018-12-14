# Theme Overview

Check `local-config-sample.php` for how to setup your basic wp constants when initializing the project.

The main theme for this system lives in `/wp-content/themes/core`.
The assets (js/css/img/fonts) for this and its child themes live in `/wp-content/themes/core`. 

If needed base child themes off of this core for the templates for now. 

To begin theme work you must first have your node_modules installed and be on the correct version of node. Check the [Node](/docs/build/node.md) and [Grunt](/docs/build/grunt.md) readmes for details on the setup here and the development tasks you need to run.

Check the [PostCSS](/docs/theme/css/postcss.md) readme for information on the css system in place.

For information on all things Javascript, check [here](/docs/theme/js/README.md)

For your system setup you must define `SCRIPT_DEBUG` as true in your `local-config.php` at root. Otherwise the front end will load minified files which wont work for dev.

## Table of Contents

* **Theme**
  * [Overview](/docs/theme/README.md)
  * [Videos](/docs/theme/videos.md)
  * [Markup and Style](/docs/theme/markup-and-style.md)
  * [Accessibility](/docs/theme/accessibility.md)
  * [Image Handling](/docs/theme/images.md)
  * [Fonts](/docs/theme/fonts.md)
  * [Icons](/docs/theme/icons.md)
  * [Twig](/docs/theme/twig.md)
  * [In-Depth Components Guide](/docs/theme/components-guide.md)
  * [Forms](/docs/theme/forms/README.md)
  * **CSS**
    * [Overview](/docs/theme/css/README.md)
    * [PostCSS](/docs/theme/css/postcss.md)
    * [Grids](/docs/theme/css/grids.md)
    * [Plugins](/docs/theme/css/plugins.md)
  * **Javascript**
    * [Overview](/docs/theme/js/README.md)
    * [Code Splitting](/docs/theme/js/code-splitting.md)
    * [Polyfills](/docs/theme/js/polyfills.md)
    * [Selectors](/docs/theme/js/selectors.md)
    * [Events](/docs/theme/js/events.md)
    * [Jquery](/docs/theme/js/jquery.md)
  * **Components**
    * [Overview](/docs/theme/components/README.md)
    * [Accordion](/docs/theme/components/accordion.md)
    * [Button](/docs/theme/components/button.md)
    * [Card](/docs/theme/components/card.md)
    * [Content Block](/docs/theme/components/content_block.md)
    * [Quote](/docs/theme/components/quote.md)
    * [Slider](/docs/theme/components/slider.md)
    * [Template](/docs/theme/components/template.md)
    * [Text](/docs/theme/components/text.md)
