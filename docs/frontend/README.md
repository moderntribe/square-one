# Theme Overview

Check `local-config-sample.php` for how to setup your basic wp constants when initializing the project.

The main theme for this system lives in `/wp-content/themes/core`.
The assets (js/css/img/fonts) for this and its child themes live in `/wp-content/themes/core`. 

If needed base child themes off of this core for the templates for now. 

To begin theme work you must first have your node_modules installed and be on the correct version of node. Check the [Node](/docs/build/node.md) and [Grunt](/docs/build/grunt.md) readmes for details on the setup here and the development tasks you need to run.

Check the [PostCSS](/docs/frontend/css/postcss.md) readme for information on the css system in place.

For information on all things Javascript, check [here](/docs/frontend/js/README.md)

For your system setup you must define `SCRIPT_DEBUG` as true in your `local-config.php` at root. Otherwise the front end will load minified files which wont work for dev.

## Table of Contents

* **Theme**
  * [Overview](/docs/frontend/README.md)
  * [Videos](/docs/frontend/videos.md)
  * [Markup and Style](/docs/frontend/markup-and-style.md)
  * [Accessibility](/docs/frontend/accessibility.md)
  * [Image Handling](/docs/frontend/images.md)
  * [Fonts](/docs/frontend/fonts.md)
  * [Icons](/docs/frontend/icons.md)
  * [Twig](/docs/frontend/twig.md)
  * [In-Depth Components Guide](/docs/frontend/components-guide.md)
  * [Forms](/docs/frontend/forms/README.md)
  * **CSS**
    * [Overview](/docs/frontend/css/README.md)
    * [PostCSS](/docs/frontend/css/postcss.md)
    * [Grids](/docs/frontend/css/grids.md)
    * [Plugins](/docs/frontend/css/plugins.md)
  * **Javascript**
    * [Overview](/docs/frontend/js/README.md)
    * [Code Splitting](/docs/frontend/js/code-splitting.md)
    * [Polyfills](/docs/frontend/js/polyfills.md)
    * [Selectors](/docs/frontend/js/selectors.md)
    * [Events](/docs/frontend/js/events.md)
    * [Jquery](/docs/frontend/js/jquery.md)
    * [React Apps](/docs/frontend/js/react-apps.md)
  * **Components**
    * [Overview](/docs/frontend/components/README.md)
    * [Accordion](/docs/frontend/components/accordion.md)
    * [Button](/docs/frontend/components/button.md)
    * [Card](/docs/frontend/components/card.md)
    * [Content Block](/docs/frontend/components/content_block.md)
    * [Quote](/docs/frontend/components/quote.md)
    * [Slider](/docs/frontend/components/slider.md)
    * [Template](/docs/frontend/components/template.md)
    * [Text](/docs/frontend/components/text.md)
