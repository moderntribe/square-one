![./example/example.svg](./logo.svg)

[![Build Status](https://travis-ci.com/moderntribe/square-one.svg?token=1evq9eFenqSy9NpYbMyT&branch=master)](https://travis-ci.com/moderntribe/square-one)

[1] is a development framework created by Modern Tribe for use in our WordPress projects. It contains a base for:

* Our build process (node, grunt, webpack)
* Theme independent core functionality plugin | /content/mu-plugins
* Core plugin | /content/plugins/core
* Core theme | /content/themes/core
* Front-end asset structure (JS, PostCSS & CSS)

## Table of Contents

* **Build**
  * [Overview](/docs/build/README.md)
  * [Node](/docs/build/node.md)
  * [Grunt Tasks](/docs/build/grunt.md)
* **Panel Builder**
  * [Overview](/docs/panels/README.md)
  * [The Initializer](/docs/panels/initializer.md)
  * [Register A Panel](/docs/panels/register.md)
  * **Default Panels**
      * [Overview](/docs/panels/default/README.md)
      * [Accordion](/docs/panels/default/accordion.md)
      * [Card Grid](/docs/panels/default/cardgrid.md)
      * [Content Slider](/docs/panels/default/content-slider.md)
      * [Gallery](/docs/panels/default/gallery.md)
      * [Hero](/docs/panels/default/hero.md)
      * [Image + Text](/docs/panels/default/imagetext.md)
      * [Interstitial](/docs/panels/default/videotext.md)
      * [Logo Farm](/docs/panels/default/logofarm.md)
      * [Micro Nav Buttons](/docs/panels/default/micronavbuttons.md)
      * [Post Loop](/docs/panels/default/postloop.md)
      * [Testimonials](/docs/panels/default/testimonials.md)
      * [Video + Text](/docs/panels/default/videotext.md)
      * [WYSIWYG](/docs/panels/default/wysiwyg.md)
* **Theme**
  * [Overview](/docs/theme/README.md)
  * [Markup and Style](/docs/theme/markup-and-style.md)
  * [Accessibility](/docs/theme/accessibility.md)
  * [Image Handling](/docs/theme/images.md)
  * [Fonts](/docs/theme/fonts.md)
  * [Icons](/docs/theme/icons.md)
  * [Twig](/docs/theme/twig.md)
    * **Css**
      * [Overview](/docs/theme/css/README.md)
      * [PostCSS](/docs/theme/css/postcss.md)
      * [Grids](/docs/theme/css/grids.md)
      * [Plugins](/docs/theme/css/plugins.md)
      * [Forms](/docs/theme/css/forms.md)
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
      * [Breadcrumbs](/docs/theme/components/breadcrumbs.md)
      * [Button](/docs/theme/components/button.md)
      * [Card](/docs/theme/components/card.md)
      * [Content Block](/docs/theme/components/content_block.md)
      * [Image](/docs/theme/components/Image.md)
      * [Pagination](/docs/theme/components/pagination.md)
      * [Quote](/docs/theme/components/quote.md)
      * [Search](/docs/theme/components/search.md)
      * [Slider](/docs/theme/components/slider.md)
      * [Template](/docs/theme/components/template.md)
      * [Text](/docs/theme/components/text.md)
      * [Title](/docs/theme/components/title.md)
      * [Video](/docs/theme/components/video.md)
* **Backend**
  * [Overview](/docs/backend/README.md)
  * [Container / Core.php](/docs/backend/container.md)
  * [Custom Post Types](/docs/backend/post-types.md)
  * [Custom Taxonomies](/docs/backend/taxonomies.md)
  * [Post & Taxonomy Meta](/docs/backend/post-meta.md)
  * [Service Providers](/docs/backend/service-providers.md)
  * [Template Controllers](/docs/backend/data.md)
  * **Plugins/Extensions**
    * [Twig](https://twig.symfony.com/)
    * [Posts 2 Post](https://github.com/scribu/wp-posts-to-posts/wiki)
    * [Extended Post Types](https://github.com/johnbillion/extended-cpts/blob/master/README.md)
    * [Extended Taxonomies](https://github.com/johnbillion/extended-taxos/blob/master/README.md)
* **Tests and Code Quality**
  * [Overview](/docs/tests/README.md)
  * [Codeception](/docs/tests/codeception.md)
  * [Jest](/docs/tests/jest.md)
* **Local Dev Environment**
  * [Docker](/dev/docker/README.md)
* **Deploys with Ansible**
  * [Overview](/docs/ansible/README.md)
  * [Terminology](/docs/ansible/terminology.md)
  * [Initial Setup](/docs/ansible/initial-setup.md)
  * [Executing Deploys](/docs/ansible/deploys.md)



#### Using the build tools

The build tools for themeing can be found in the *root* directory. Refer to the docs for instructions on getting started with Grunt, Webpack, and PostCSS.


## Configurations


*Production*

When using this on a production environment, be sure to add the following to your local-config.php:
    define( 'ENVIRONMENT', 'PRODUCTION' );

*Development*

GLOMAR is a plugin that blocks the frontend of the site from public access. If you would like to disable the plugin locally, add the following to your local-config.php:
    define( 'TRIBE_GLOMAR', false );


## Changelog

[View changelog](./changelog.md)
