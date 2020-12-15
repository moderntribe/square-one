# Directory Structure

## Core Plugin

The Core plugin, found in `wp-content/plugins/core`, contains the application logic we use to customize
WordPress's behavior. The method `\Tribe\Project\Core::init()` serves as our entrypoint into the application.

All classes in the Core plugin follow the [PSR-4](https://www.php-fig.org/psr/psr-4/) specification for class namespaces.
`wp-content/plugins/core/src` is the root of the `\Tribe\Project` namespace.

The `Core` class is responsible for initializing the [DI container](../concepts/container.md) and calling
all of the [Subscribers](../concepts/subscribers.md) to register their hooks with WordPress. From here, things become
much less linear, so we'll need to branch out in a few different directions.

* `\Tribe\Project\Assets`: Theme assets for the front end and admin are registered and enqueued in
  `\Tribe\Project\Assets`. 
  
* `\Tribe\Project\Blocks`: [Block types](../basics/blocks.md) for the block editor are defined by classes in the
  `\Tribe\Project\Blocks\Types` namespace and registered by adding to the list in
  `\Tribe\Project\Blocks\Blocks_Definer`.
  
* `\Tribe\Project\Cache`: Cache invalidation listeners are registered in `\Tribe\Project\Cache\Cache_Subscriber` to
  trigger callback methods in the `Listener`.
  
* `\Tribe\Project\Integrations`: Integrations with 3rd-party plugins and services are managed by classes in
  the `Integrations` directory. Each plugin/service should be wrapped in its own namespace.

* `\Tribe\Project\Nav_Menus`: Navigation menus are registered in `\Tribe\Project\Nav_Menus\Nav_Menus_Definer`.

* `\Tribe\Project\Object_Meta`: To register new meta groups for post types, taxonomies, settings screens, users,
  or navigation menus, add a class in the `\Tribe\Project\Object_Meta` namespace and add a definition in
  `\Tribe\Project\Object_Meta\Object_Meta_Definer`.
  
* `\Tribe\Project\P2P`: If using the [Posts to Posts](https://github.com/scribu/wp-posts-to-posts/wiki) plugin,
  new relationship types are registered using classes in the `\Tribe\Project\P2P` namespace.

* `\Tribe\Project\Post_Types`: All of the [post types](../basics/post-types.md) we interact with have classes in
  the `\Tribe\Project\Post_Types` namespace. `Config` files will configure custom post types that we register.
  Classes extending `\Tribe\Libs\Post_Type\Post_Object` for each post type provide access to post meta and other
  helpful methods relating to those post types.
  
* `\Tribe\Project\Settings`: New [admin settings](../basics/settings.md) pages are registered with classes in the
  `\Tribe\Project\Settings` namespace.
  
* `\Tribe\Project\Shortcodes`: Shortcodes are registered with classes in the `\Tribe\Project\Shortcodes` namespace.

* `\Tribe\Project\Taxonomies`: All of the [taxonomies](../basics/taxonomies.md) we interact with have classes in
  the `\Tribe\Project\Taxonomies` namespace. `Config` files will configure custom taxonomies that we register.
  Classes extending `\Tribe\Libs\Taxonomy\Term_Object` for each taxonomy provide access to term meta and other
  helpful methods relating to those taxonomies.
  
* `\Tribe\Project\Templates`: The Controllers under the `\Tribe\Project\Templates\Controllers` namespace are responsible
  for loading the [template components](../concepts/components.md) with appropriate data.

* `\Tribe\Project\Theme`: To configure various theme settings, explore the `\Tribe\Project\Theme` namespace. Of
  particular note are the configuration options for colors, gradients, and fonts available in
  `\Tribe\Project\Theme\Theme_Definer`, as well as the image sizes defined in `\Tribe\Project\Theme\Config\Image_Sizes`.

## Core Theme

The Core theme is found in `wp-content/themes/core`.
 
In the root of the theme, you will find the traditional files of the [WordPress template
hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/). While all of the files are loaded by
WordPress just as one would expect, inside the files you will find a single call to `tribe_template()`, the function
responsible for loading the template controllers and, ultimately, the theme components found, ironically enough, 
in the theme's `components` directory.

The components themselves are arranged in a largely flat structure, with a few deviations for components that may
be naturally grouped together (e.g., page templates). Each component's directory contains its own JS and CSS, which
are compiled into the `assets` directory by the [build system](../concepts/build-system.md).

Assets that are globally relevant are also contained in the `assets` directory and compiled into the same `dist` files.

As with the Core plugin, components to integrate the theme with 3rd-party plugins and services should be contained
within the `integrations` directory. These components, too, will be compiled into the `dist` assets.

## Dev Tools

Outside of the Core plugin and Core theme, most of the project consists of development tools. Some notable places to
mention:

* `dev/deploy`: Scripts to deploy the project from Jenkins.

* `dev/docker`: Configures the Docker container for running the project.

* `dev/tests`: Contains the [codeception test suites](../tests/codeception.md).

* `dev_componetns`: Resources for theme development.

* `docs`: This documentation.
