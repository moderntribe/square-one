![./example/example.svg](./logo.svg)


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
* **Theme**
  * [Overview](/docs/theme/README.md)
  * [Markup and Style](/docs/theme/markup-and-style.md)
  * [Accessibility](/docs/theme/accessbility.md)
  * [Image Handling](/docs/theme/images.md)
  * [Fonts](/docs/theme/fonts.md)
  * [Icons](/docs/theme/icons.md)
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
* **Backend**
    * [Overview](/docs/backend/README.md)
      * [Container / Core.php](/docs/backend/container.md)
      * [Custom Post Types](/docs/backend/post-types.md)
      * [Custom Taxonomies](/docs/backend/taxonomies.md)
      * [Post & Taxonomy Meta](/docs/backend/post-meta.md)
      * [Service Providers](/docs/backend/service-providers.md)
      * [Template Controllers](/docs/backend/data.md)
    * Plugins/Extenstions
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



#### Using the build tools

The build tools for themeing can be found in the *root* directory. Refer to the docs for instructions on getting started with Grunt, Webpack, and PostCSS.


## Configurations


*Production*

When using this on a production environment, be sure to add the following to your local-config.php:
    define( 'ENVIRONMENT', 'PRODUCTION' );

*Development*

GLOMAR is a plugin that blocks the frontend of the site from public access. If you would like to disable the plugin locally, add the following to your local-config.php:
    define( 'TRIBE_GLOMAR', false );


## Upgrade Notice

= 1.1 =
Moved the project dependencies to Composer  
Added Codeception tests examples and instructions  
= 1.0 =
Initial Release


## Changelog


= 1.1 =
Moved the project dependencies to Composer  
Added Codeception tests examples and instructions  
= 1.0 =
Initial Release

