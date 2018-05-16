# Grunt Tasks

This system uses grunt to run tasks. Make sure you have installed `node_modules` at root with `npm install` using the correct version of node for the project (check the .nvmrc file at root to determine that and check the [node guide](/docs/guides/node.md) here for details).

If you don't already have the [grunt-cli](https://github.com/gruntjs/grunt-cli) installed globally on that node version do so.

During dev you have 2 choices for grunt tasks to run when editing any pcss or javascript files.

* `grunt watch` will watch pcss and js for changes and trigger live-reload 
* `grunt dev` will watch pcss and js for changes and launch [Browsersync](https://www.browsersync.io/) on localhost:3000. It will proxy through the dev url you can define in `local-config.json` at root. Check the local-config-sample.json for more information.

Before you push code back upstream, you must run `grunt dist`. This will create the bundled files and minify assets. It will also lint you js and css. If you code does not lint please correct the issues then run again before pushing upstream.

## Table of Contents

* [Overview](/docs/build/README.md)
* [Node](/docs/build/node.md)
* [Grunt Tasks](/docs/build/grunt.md)