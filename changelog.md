# Changelog

All notable changes to this project will be documented in this file.

## 2022.08
* Fixed: Branches that have Jira projects in them that contained numbers in the name would not properly prefix git commits, e.g. `feature/DATA22-3/some-new-feature`


## 2022.07
* Fixed: TinyMCE floating toolbar repositioning loop. https://core.trac.wordpress.org/ticket/44911
* Fixed: Set Static Analysis format for GitHub in Actions.
* Added: Block Category (Custom) for all custom blocks.
* Updated: Block fields layout and language for consistency.
* Updated: Removed `image_select` fields in favor of `button_group` fields on blocks.
* Added: MVP Site Footer.
* Updated: ACF (5.12.3), Gravity Forms (2.6.4), Disable Emojis (1.7.4), Limit Login Attempts Reloaded (2.25.3), Post 2 Posts (1.7), User Switching (1.6.0), Yoast SEO (19.3)
* Fixed: A typo in the default theme name.
* Updated: Move General Settings to Appearance > ["Theme Name" Options and adjust Analytics & Social Media usages appropriately. 
* Fixed: Embeds in reusable blocks not displaying on the frontend. https://core.trac.wordpress.org/ticket/46457
* Added: Core Columns block with support for headings, paragraphs, lists, and images.
* Updated: Base kitchen sink styles for improves specificity and admin support.
* Fixed: Misnamed Buttons Block classes and attached button styles
* Removed: Legacy "unsupported browser" feature and all related build system features.

## 2022.06
* Fixed: Erroneous link clicks in card.js for mouse right-clicks on Windows. 
* Bumped: Tribe Libs to 3.4.18 to update block generators to escape labels.
* Added: PHP side functionality and filter for unregistering block styles.
* Fixed: Replaces `__()` with `esc_html__()` in `wp-content/plugins/core/src/Blocks` folder
* Added: Text alignment mixins & classes to support Gutenberg.
* Fixed: Filter Social items to prevent empty HTML output.
* Fixed: Remove `SECTION_CONTENT` constant from `Stats Block`.
* Fixed: Blocks added below floated elements in Gutenberg should now properly clear on both the frontend and backend.
* Fixed: Gravity Forms spin.js spinner should now properly work for paginated forms.
* Added: [phpstan/phpstan-mockery](https://github.com/phpstan/phpstan-mockery)
* Fixed: Tab block controller throwing type errors.
* Fixed: `update-query-var.js` can now properly remove keys with `undefined` values.
* Added: Youtube icon to core icons set.

## 2022.05
* Fixed: Use PHP to prefix commits with a jira ticket, avoiding different shell environments.
* Fixed: Prevent PHPStan from running out of memory during CI runs.
* Updated: Recommended extensions for VS Code to include PHP Intelephense. 
* Added: Access to the container in integration tests via `$this->container`.
* Updated: Recommended extentions for VS Code to include PHP Intelephense. 
* Updated: `.phpstorm.meta.php` code completion documentation.
* Fixed: Ensure setup-node github action can find the yarn.lock file for caching.
* Updated: Ensure object meta definers are using autowiring for automatic dependency injection. 
* Added: Brings in the [square1-field-models](https://github.com/moderntribe/square1-field-models) library.
* Updated: WordPress to 5.9.3, ACF to 5.12.2 and Yoast to 18.8.
* Fixed: Hides deprecation notices when running lefthook phpcs, which appear if you're running PHP8.1 locally.
* Fixed: Converts all blocks/components to use Field Models, where appropriate.
* Fixed: Prevent WP Core from adding `loading="lazy"` to our image component `<img />` tags.
* Updated: Remove old TinyMCE image tag filtering and related unit test.
* Fixed: Images lazy loaded via LazySizes are now loaded before printing.

## 2022.04
* Fixed: Remove autoprefixer run from cssnano task as it erroneously strips line-clamp properties.
* Added: An ACF helper class (`tribe-counter-wrapper`) that triggers a maxlength counter on the field.
* Added: node and composer caching for GitHub actions
* Updated: Webpack & related configs for React HMR / Example react app dev.
* Fixed: deploy commit messages via GitHub actions now display the branch and environment instead of the variables.
* Changed: all block models are now instantiated with the container to support dependency injection.
* Added: PHPStan for static analysis.
* Added: Recommended Extensions for VS Code.

## 2022.03
* Fixed: Refactor index controller to use proper meta fetching objects and general code clean up.
* Fixed: Show same header logic on the Post Tag archive as the git Category Archive.
* Added: Ignore the Query Monitor plugin's automatically created db.php file.
* Added: Pagination Helper Trait.
* Fixed: .editorconfig incorrect tabbing for PHP files (thanks Caleb).
* Fixed: Missing pagination on the content loop block component.
* Fixed: Visibility of public $sidebar_id's in all controllers.
* Updated: Misc config updates for Dokku and Docker nginx & PHP.
* Fixed: Gravity Forms filter parameter types changed for v2.5 and above.
* Updated WordPress: 5.9.2 / new tests dump.sql
* Updated plugins: ACF, TEC, Gravity Forms, Yoast
* Updated: Tribe Libs to 3.4.12
* Fixed: Corrects parameter type for P2P cache method
* Fixed: Adds styling for nested lists in t-sink context
* Changed: Replaced `msawicki/acf-menu-chooser` with a forked https://github.com/moderntribe/acf-menu-chooser that includes security fixes and is also added to packagist.
* Updated: ACF (5.12), Tribe Libs (3.4.10), Redirection (5.2.3), Yoast (18.2), TEC (5.14.0.4)
* Updated: Aligns accordion component with WAI-ARIA standard
* Fixed: Jetpack sync calls the `all_plugins` filter outside of a screen context, causing fatal errors in our force plugin activation MU plugin when using multisite.
* Added: Enable/disable scrolling behavior on accordion blocks

## 2022.02
* Updated: Coding standards to v2.1.2, PHP8 sniffer fixes.
* Updated: Added composer patches to allowed composer plugins.

## 2022.01
* Added: Custom "so project:test" command to run all automated testing suites (requires `so` 5.5.0+)
* Added: `proxy_ssl_server_name on;` in nginx.conf to allow proxying to production domains if they are using Cloudflare.
* Fixed: phpcs GitHub workflow using the wrong secret.
* Added: An empty CLI_Definer to easier register commands.
* Updated: added allowed plugins to composer.json
* Updated: coding standards to [version 2](https://github.com/moderntribe/coding-standards/tree/2.0.x).
* Updated: WordPress 5.7.3 > 5.8.3 (security release)
* Updated: ACF Pro 5.10.2 > 5.11.4
* Updated: TEC 5.10.1 > 5.12.2
* Updated: Yoast 17.5 > 17.8
* Removed: Service Worker (PWA) support
* Updated: Ensures Swiper.js `imagesReady` event listener is always added during initialization of a new Swiper instance.
* Fixed: Social share component now properly triggers popup when more than one component is present on a page.
* Updated: Fixed several components whose JavaScript could initialize even when component isn't present on a page.

## 2021.12
* Fixed: docker compose (v2) support: `WARN[0000] network proxy: network.external.name is deprecated in favor of network.name`
* Added: Section Nav component & block.
* Added: generic navigation component.
* Updated: Node & NPM to latest LTS (v16.13.1), updated node package versions where supported.
* Fixed: A possible PHP fatal when a nav menu is cached, but empty and the cache layer returns an unexpected boolean instead of an empty string.
* Fixed: Several minor deprecation warnings in the webpack config.
* Fixed: prevented preloading of current document when a dependency alias is enqueued in the footer
* Added: support to preload dependencies of aliases

## 2021.11
* Removed: monolog. This dependency will be managed by tribe-libs.
* Updated: tribe libs to 3.4.8.
* Fixed: Lefthook - Don't prefix commits for sprint or release branches
* Updated: docker image to 74-3.0, composer v2 support (requires `so` v5.3.0+)
* Added: Entrypoint for component scripts to run in the block editor.
* Added: Slider component JS behaviors in the block editor.
* Added: check that `theme_location` parameter of `wp_nav_menu()` is populated before adding classes
 
## 2021.10
* Fixed: Misc small repairs to common blocks per QA on other projects.

## 2021.09
* Fixed: Style guide typography regression caused by Core's reset.css enqueuing in the block editor with the v5.8.0 release of WordPress.
* Updated: ci GitHub action to allow manual runs
* Fixed: Replace deleted repo https://github.com/hautelook/phpass with https://github.com/bordoni/phpass
* Updated: wp-browser to 3.0.9
* Fixed: Reusable & Group block width repair.
* Added: Generic navigation menu component.

## 2021.08
* Added: Block Deny List, removing the requirement to list all allowed blocks.
* Added: Post Single Template and styles.
* Fixed: Image Component Caption display bug
* Fixed: Glomar Cookie Constant issue
* Updated: ACF block field sets for better UX.
* Added: Routes to the gulp build system

## 2021.07
* Updated: WordPress 5.8
* Added: Disabled Block widgets from wp5.8
* Updated: Allow block filter change for wp45.8
* Fixed: SQONE-652: webpack config for dynamic imports fixed for dev tasks.
* Fixed: SQONE-658: Update PostCSS config to remove duplicate selectors.

## 2021.06
* Added styles and data handling for the index and archive pages.
* Updated: Moved Post Archive settings from General Settings to post archive settings
* Added Styles for the Not Found Page
* Added: Styles and additional functionality to the search template.
* Fixed: PHPCS workflow from not running when set as "Require status checks to pass before merging" if no files changed;
preventing a PR from being able to be merged.

## 2021.05
* Added: herokuish deployment support (dokku)
* Fixed: Husky hooks commits from scanning all PHP files with phpcs and limits to our core plugin/theme.
* Fixed: broken main README.md links
* Removed: husky hooks.
* Added: lefthook git-hooks (see lefthook.yml and .lefthook folder) to replace husky hooks: https://github.com/evilmartians/lefthook
* Added: run phpcs checks on commit via lefthook. 
* Added: prefix commit messages with the Jira ticket from the branch.  
* Updated: Move phpcs to https://github.com/moderntribe/coding-standards
* Removed: Broken Gallery shortcode/subscriber.
* Removed: phpcs.xml, this should be git ignored.
* Added: phpcs.xml.dist with new coding standards (mostly formatting based for now).
* Updated: Updated all relevant PHP files for phpcs.
* Updated: .editorconfig to better match new phpcs formatting.  
* Removed: mercator mu plugin https://github.com/humanmade/Mercator as multisite domain mapping has been in core for some time.
* Updated: workflows/phpcs.yml to use the new coding standard in GitHub workflows  
* Fixed: Multiple blocks keywords not using `__()` properly.
* Added: documentation to get PHPCS configured in VS Code.
* Updated: use the WP_ENVIRONMENT_TYPE constant added in Core v5.5.
* Added: enabled the legacy markup for Gravity Forms 2.5 by default (until we can update our theme framework).
* Fixed: small issue with webpack publicPath's being off.
* Fixed: removed unnecessary Sage SVG plugin from composer.
* Fixed: updated the Limit Login Attempts path for force-activated plugins.
* Updated: Increase the width of the block sidebar
* Updated: Spruce up the Repeater field, especially when used in ACF Block Sidebars
* Updated: Spruce up the Image Field
* Added: a utility class of ‘tribe-acf-hide-label’ to hide field labels in cases where they are unnecessary/exterraneous
* Added: a utility class of ‘tribe-acf-instructions’’. Apply to a Message field if you need ‘spoof’ ad-hoc instructional content.
* Fixed: Converts the button block to now use the button_group acf field time.

## 2021.04
* Updated: refreshed local-config-sample.php to have a more thorough set of constants you may need, especially now that we have a Local environment option.
* Updated: newer version of spin.js requires some css, added that.
* Updated: set a default set of theme image paths for postcss-inline-svg
* Updated: disabled cssnano discarding "unused" keyframes. It was borking the spin.js css.
* Updated: remove the tribe/body_opening_tag action in favor of wp_body_open and updated our GTM implementation to use it.
* Fixed: body-lock.js needed an update to work correctly for safari/ios.
* Updated: added a isLocked check for body-lock.js, it's handy.
* Updated: docker php-ini-overrides.ini to use xdebug v3 configuration for latest docker builds.

## 2021.03
* Updated: lead form block to use Gravity Forms child / inner block.
* Updated: updated swiper to latest and addressed slider component issues.
* Updated: postcss gulp config to preserve custom properties and to allow for native use of focus-visible and focus-within.
* Updated: Updated WP Core to WP 5.7. Various plugin updates. Renamed Github actions to improve readability.
* Fixed: Media + Text block icon image asset path.

## 2021.02
* Fixed: An issue with the callback used in the `scroll-to` script.
* Added: Handling for broken browser implementation of skip links and adding automatic focus switching for anchor hash links for a11y.
* Fixed: Add header and footer templates to ensure default TEC templates will load.
* Updated: Adds Husky hooks for linting before committing code.

## 2021.01
* Updated: selenium/standalone-chrome docker image to a tag that is updated frequently.
* Fixed: CI GitHub Workflow failing and will collect artifacts if webdriver tests randomly fail for debugging.
* Added: Support for the block editor's "HTML Anchor" field on our custom ACF blocks.
* Fixed:  An issue where some of our block controllers were using the `class_attribute()` utility method to return attributes. (Block controllers are all correctly using `concat_attrs()` now.)

## 2020.11
* Updated: docker-compose.yml php-fpm image from 74-2.1.1 to 74-2.1 to ensure composer v1.
* Updated: Adds core/block to allow list to enable reusable blocks.
* Updated: Order the block allow list alphabetically.

## 2020.10
* Fixed: Typo in object-cache docs. 


## 2020.09
* Updated: Various codebase configs.
    * local-config-sample.php: cleaned up no longer needed items
    * .env.sample: fixed typos and now incorrect vault reference
    * Updated caniuse-lite to latest version
    * Updated our browserslist to be more in line with testing policy (package.json, postcss, babeljs)
    * wp-config.environment.php: updated usage of SCRIPT_DEBUG
* Removed: The Image and Image_Derivative models are gone. The Image_Controller for
  the image component no longer accepts an "attachment" argument, opting instead
  for an "img_id" pointing to the WP attachment post.
* Changed: Update block components to allow passing either a string or ID for image.
* Fixed: Image_Controller no longer tries to lazyload images passed as strings.
* Changed: All-the-things. Major changes in support of our "Fidgety Feet" epic.
    * Removed Twig
    * Removed Pimple in favor of PHP's built-in autoloading.
    * Theme structure & build system updates
    * Added support for WP Core's block editor
    * Removed Panel Builder in favor of Gutenberg blocks.
    * Added ACF-based "Common Blocks"
    * Much more.

## 2020.04
* Updated: Force plugin activation comments
* Added: JenkinsFile for pipeline build and deployment to hosted environments with Git Deploys
* Updated: bash deployment script for locally run deployments to environments with Git deploys 
* Updated: Certificate creation default date to meet new requirements from [Ballot 193](https://cabforum.org/2017/03/17/ballot-193-825-day-certificate-lifetimes/).
* Fixed: Uses a set version for Alpine in order to have a constant call to nslookup for docker.for.mac.localhost. 

## 2020.03
* Added: PHP rules for .editorconfig, including config for many PhpStorm rules
* Fixed: Broken composer.lock file preventing Gravity Forms installation

## 2020.02
* Added: Bash script to run tests in the Container
* Changed: Invalidate object cache salt on tests-config-sample.php
* Changed: Abstracted repetitive test parameters into codeception.suite.yml
* Changed: Updated tests "dump.sql" file
* Changed: Ignored tests "_generated" folder
* Added: Added new Page Object test examples
* Added: Server name on PHP env, so Xdebug works on CLI
* Fixed: All tests are passing
* Fixed: Tests are passing on Travis
* Updated: Raised minimum wp-browser version to 2.2.36
* Changed: Specified the Selenium Chrome version on Global to 3.141.59
* Updated: s3-uploads plugin to 2.2.1
* Changed: Refactored the image component to reduce complexity and allow more robust usage options and made a general pass at code clean up & documentation.
* Changed: all uses of `the_tribe_image()` ion the core plugin have been refactored to use the image component directly.
* Updated: Docs for Image Component, theme Images to reflect current architecture.
* Fixed: Removed almost all references to Grunt in the docs because it is switched to Gulp. The only references left are for the outdated videos.
* Added: Several helper methods to the Theme Colors class for working with color arrays.

## 2020.01
* Changed: Improve composer performance: Load hirak/prestissimo globally, volume mount entire composer dir, use "no-api": true, for VCS repositories, fix gravity forms installer.
* Fixed: Sidebar.php template from calling Base::get_data() needlessly in the same request
* Add: Added example eslint and phpcs github workflows
* Fixed: Typo in docker logs naming for NPM scripts. 

## 2019.12
* Changed: fixed main documentation links and updated WP CLI xdebug docs
* Changed: set wpx.sh to executable
* Updated: Node & NPM to latest LTS versions and all FE build tooling to latest (compatible) package versions. Related misc FE build tooling tweaks to accommodate new package versions.
* Changed: Removed postcss custom property path vars in favor of postcss-assets plugin because [custom properties are not supported in `url()`'s](https://stackoverflow.com/a/42331003/1135190) per the CSS spec.

## 2019.11
* Added `TRIBE_DISABLE_PANELS_CACHE` to `local-config.php`
* Removed: Google+ (deprecated) support for social sharing and following functionality
* Added container component to allow for more composition flexibility
* Added ifdef loader for Webpack to allow exclusion of React app chunk generation during main js bundle dev work
* Changed: update docker `start.sh` script to check for a root `.env` file
* Removed: SVG_Support Class and Util Provider
* Added: Plugin: Safe SVG
* Updated: WordPress to 5.2.4
* Updated: Plugin: gravity-forms-wcag-20-form-fields to 1.7.2
* Updated: Plugin: regenerate-thumbnails to 3.1.1
* Updated: Plugin: the-events-calendar to 4.9.10
* Updated: Plugin: wordpress-seo to 12.4
* Updated: Plugin: user-switching to 1.5.2
* Updated: Plugin: classic-editor-addon to 2.5.0
* Changed: added check to panels caching to avoid caching on panel preview

## 2019.10
* Changed: Updated core plugin to work with the Tribe Libs monorepo
* Changed: Loosened version constrain on Tribe Libs packages to "^2.0"

## 2019.09
* Changed: Cleaned up the `Page_Title` Class for readability and code standards

## 2019.08
* Fixed: Don't ignore sub folders called wp
* Changed: sq1 Twig classes to use non-deprecated Twig classes and bump twig minimum to v2.11

## 2019.07
* Updated: Lodash to 4.17.14
* Removed 3rd-party premium plugins, added composer installer for them
* Add direct 1password link in .env.sample
* Removed 3rd-party premium plugins, added composer installer for them
* Added "prefer-stable" to composer.json to resolve failing installs
* Re-organized docs. Added getting started docs for new folks

## 2019.06
* Fixed: Typo dash in modern-tribe removed from set remote in convert-project-to-fork.sh
* Changed: Updated convert-project-to-fork.sh to use ssh instead of https for setting remote url
* Add a utility for generating required pages
* Changed: Updated the Image Component and `the_tribe_image()` method to accept an image URL fallback
* Fixed: Restored returning an image size that is an exact match to the full size image, when that size is specifically requested.

## 2019.05
* Changed: Updated Classic Editor Plugins to latest Versions
* Updated: Swiper to version 4.5.0
* Changed: Added autoprefixer support for CSS Grid 
* Changed: Updated WordPress core to 5.1.1
* Changed: Updated ACF to 5.7.13
* Updated WordPress to 5.2.1
* Fixed: Tribe Branding plugin: updated deprecated `login_headertitle` filter to use `login_headertext` instead

## 2019.04

* Changed: Added npm commands for running codeception tests
* Changed: Added in more formal documentation and reasoning behind Lazy Loading classes in our Service Providers.
* Changed: Updated default PHP version to 7.2
* Changed: Added in default Panels Caching
* Changed: Added in Whoops library
* Add `WP_DISABLE_FATAL_ERROR_HANDLER` constant to wp-config to disable the fatal error handler

## 2019.03

* Updated to node 10.15.0
* Updated to use Gulp
* Updated to use Products linting standards for js, react and pcss
* Eslint and Stylelint now first attempt to fix all linting errors before running
* Added Bugsnag integration
* Added React app injection into main js bundle
* Added example react app
* Added wider alias support for common js directories
* Breaks up webpack into a modular setup
* Added webpack bundle analyzer reports
* Moves swiper to non webpack loading as it has issues with webpack.
* Changed: .travis.yml to properly run integration, acceptance and web driver tests and set a github token for dependencies
* Changed: .travis.yml to create cache directories and add memcached servers to local-config.php 
* Changed: tests-config-sample.php to disable admin SSL 
* Changed the documentation to mention "global" when it comes to the script for the certificates generation
* Added references to queues and schema to the docs general overview
* Added steps to follow on Linux environments to avoid problems with DNS Resolution when setting up the global Docker environment.

## 2019.02

* Add bcrypt password hashing: https://github.com/roots/wp-password-bcrypt
* Refactor force-plugin-activation.php to allow forcing plugins off when running unit tests
* Added tribe-chrome global container for chromedriver acceptance testing
* Redid codeception config and added sample acceptance and webdriver tests
* Change object-cache-sample.php to object-cache.php for default inclusion on forked projects
* Update incorrect global setting for memcached_server in local-config-sample.php

## 2019.01

* Added a meta importer CLI command.
* Fixed: Add sintax hightlight for docs with code examples
* Replaced the Pimple Dumper with a container exporter to work with recent versions of PhpStorm
* Fixed: Set explicit charset and collation for queues MySQL table
* Fixed: Handled invalid records in the queue table, avoiding a fatal error
* Modified: Test Suite configuration by moving it to the dev directory and consolidating composer management.

## 2018.12

* Added: caption position support to video component
* Fixed: gitignore entry for Tribe 301 plugin
* Added the Blog Copier
* Changed: added more documentation for our SQ1 forms implementation
* Fixed failing test for Full_Size_Gif

## 2018.11

* Fixed: Nginx config to properly pull missing assets (images/js/css/media etc...) from a remote server so you don't need to download large uploads folders.
* Changed: Cleaned up the `CLI_Provider`
* Changed: Introduced the `Generator_Command` abstract class for generator commands, so that we don't need all commands following the same constructor.
* Added: the following JavaScript unit tests: accessibility.test, apply-browser-classes.test, body-lock.test
* Fixed: Instructions to view the project logs inside docker
* Added: install guide for Ubuntu users on `docs/docker` documentation

## 2018.10

* Changed: Glomar now sets what looks like a logged in cookie to bypass Wp Engine's Varnish.
* Fixed: Autoplay for iframe video embeds in Chrome.
* Fixed: global start.sh script to properly get the docker IP address on linux

## 2018.09

* Added an incremental increase to the delay for reprocessing a failed task in the queue, based on the number of times it has failed.
* Changed: Docker setup docs
* Added: Alias docker scripts with `npm` for use in any project directory.
* Added: Fork project script: `dev/bin/convert-project-to-fork.sh`

## 2018.08

* Changed: Updated WordPress to 4.9.8
* Fixed: SVG_Support no longer throws errors in PHP 7.2
* Changed: Deferred get_content in Twig controller usage so various WordPress FE resources are loaded in the correct order
* Added: Object-meta documentation to add `nav_menus` and `nav_menus_items`
* Added: [`dev/docker/wpx.sh`](dev/docker/wpx.sh) WP CLI with xdebug script and updated [documentation](dev/docker/README.md).
* Added: PostCSS partials for the social share & follow components
* Fixed: Updated MediaElement styling for the embedded audio/video player
* Removed: PostCSS Lost Grid plugin and PostCSS settings
* Added: Classic Editor and Classic Editor Addon plugins.
* Added: Analytics ACF settings to the "General Settings" page with a GTM field and FE/theme integration.
* Added: the build process include in wp-config.php for FE asset cache busting.
* Added: postcss-preset-env to replace the now deprecated postcss-cssnext.
* Removed: deprecated postcss-cssnext
* Added: Theme/Full_Size_Gif - filters image_downsize to provide full size gifs only. It is commented out in the Theme service provider by default.

## 2018.07

* Added: social media ACF settings
* Added: social follow data to the base controller
* Changed: Updated Instagram and Google+ Icons
* Changed: Updated the Social Follow twig template
* Added: a new server_dist grunt task that excludes yarn installing
* Changed: Now updating build-process.php when performing any grunt compilation task to aid with performant deploys
* Changed: Updated WordPress to 4.9.7
* Added: Ansible boilerplate directory
* Removed: manifest.js enqueue from admin scripts. This was a remnant from the previous version of Webpack which was updated to v4.5.0 in v1.2.

## 2018.06
* Changed: JS slide util - Changed delay-based slide to RAF-based
* Added: Queues framework. This allows for running slower processes separately from a browser request.
  * Queue - A queue requires a backend for storing messages that passed to it.
  * Backends - MySQL is the default backend, but it is easy to implement the Backend interface and roll your own.
  * Messages (or Tasks) - Isolated functions that are run by the queue consumer.

## 2018.05

A whole lot of things have been added. The timing of when they were added and the corresponding versions would be too laborious to uncover. So...we'll start with some recent changes/additions.

* Added: Permissions Framework
* Added: TravisCI config file so tests get built when creating/updating a PR
* Added: Taxonomy List component
* Changed: Switched to composer for WP rather than submoduling
* Changed: Updated to webpack 4.5.0
* Changed: Disable `SCRIPT_DEBUG` by default, enable for gardens
* Changed: Remove GF WCAG and Regenerate plugins from version control
* Changed: Fixed issue with the install path for composer
* Changed: Synchronize VM time with system time when container starts
* Changed: Use new 1.1.1.1 DNS instead of Google's

## 2015.03–2018.04

* Long journey in the unversioned wilderness

## 2015.03

* Changed: Migrated to github, all history collapsed into the initial commit
* Added: Codeception test examples and instructions
* Changed: Moved the project dependencies to Composer

