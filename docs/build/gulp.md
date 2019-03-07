# Gulp Tasks

This system uses gulp to run tasks. Make sure you have installed `node_modules` at root with `yarn install` using the correct version of node for the project (check the .nvmrc file at root to determine that and check the [node guide](/docs/build/node.md) here for details).

If you don't already have the [gulp-cli](https://www.npmjs.com/package/gulp-cli) installed globally on that node version do so.

If you have gulp installed globally as well, use this projects gulp version when running tasks by using `./node_modules/gulp/bin/gulp.js` instead og `gulp`

During dev you have 2 choices for gulp tasks to run when editing any pcss or javascript files.

* `gulp watch` will watch pcss and js for changes and trigger live-reload
* `gulp dev` will watch pcss and js for changes and launch [Browsersync](https://www.browsersync.io/). It will proxy through the dev url you can define in `local-config.json` at root. Check the local-config-sample.json for more information. Also set your path to the certs you generated for your docker domain.

Before you push code back upstream, you must run `gulp dist`. This will create the bundled files and minify assets. It will also lint your js and css. If you code does not lint please correct the issues then run again before pushing upstream.

You should also run dist when you pull new work, to make sure you have built the latest files from other peoples work.

Also of note: `build-process.php` *must* contain an entry for the versioning to work correctly. Furthermore, you _need to include it_. You can add the following to your `wp-config.php`:

```php
if ( file_exists( dirname( __FILE__ ) . '/build-process.php' ) ) {
	include( dirname( __FILE__ ) . '/build-process.php' );
}
```

## Table of Contents

* [Overview](/docs/build/README.md)
* [Node](/docs/build/node.md)
* [Gulp Tasks](/docs/build/gulp.md)
* [Composer](/docs/build/composer.md)
