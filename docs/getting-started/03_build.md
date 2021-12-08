# Build Process

## Quick Start

1. Install global build tools
   1. nvm
   1. yarn
   1. gulp-cli
1. Build frontend development assets
   1. `nvm use`
   1. `yarn install`
   1. `gulp dist`
1. Install backend development dependencies
   1. `composer install` or `so composer install` if using SquareOne Global Docker

> Note: Make sure your composer version matches the project requirments. As of Oct 2021, Composer v2.

## Building Frontend Assets

### Node

This system uses npm for dependency management for front end assets.

To find out which version you should be using, check the .nvmrc file at root. We recommend
the version manager [nvm](https://github.com/creationix/nvm) to manage your node versions.

With that installed you can just `nvm use` in the root of this folder before beginning dev
tasks that involve node and it will put you on the correct version, after it has been installed
by you initially.

To install your dependencies it is recommended you use Yarn, a global cli tool you install that
fetches deps from npm, bower or other repos. First install globally in your currently required
version of node if not already in place: [install yarn](https://yarnpkg.com/en/docs/install).

Then simply `yarn install` in the root of this project.

### Gulp

This system uses gulp to run tasks. Make sure you have installed `node_modules` at root or theme with `yarn install:theme` using the correct version of node for the project (check the .nvmrc file at root to determine that).

If you don't already have the [gulp-cli](https://www.npmjs.com/package/gulp-cli) installed globally on that node version do so.

If you have gulp installed globally as well, use this projects gulp version when running tasks by using `./node_modules/gulp/bin/gulp.js` instead of `gulp`

During dev you have 2 choices for gulp tasks to run when editing any pcss or javascript files.

* `gulp watch` will watch pcss and js for changes
* `yarn dev` will watch pcss and js for changes and launch [Browsersync](https://www.browsersync.io/). It will proxy through the dev url you can define in `local-config.json` at root. Check the local-config-sample.json for more information. Also set your path to the certs you generated for your docker domain.

Before you push code back upstream, you must run `yarn validate`. This will lint your js and css. If you code does not lint please correct the issues then run again before pushing upstream.

You should also run `yarn dist` when you pull new work, to make sure you have built the latest files from other peoples work.


## Installing PHP dependencies

### Composer
We use [Composer](https://getcomposer.org/) to maintain PHP dependencies. This allows us to **not** version
control stuff that is already version controlled elsewhere.

### Installing Dependencies via Composer

On host machine:
`composer isntall`

If using SquareOne Global Docker:
`so composer install`

> Note: Make sure your composer version matches the project requirments. As of Oct 2021, Composer v2.

### Composer in Docker
Traditionally, composer projects use the native `composer` command (e.g. `composer install`,
`composer update`). Because SquareOne operates in Docker containers, we cannot rely on composer
to behave appropriately in all environments because *composer runs on the host machine, and not
in the docker container that has all the expected system requirements such as the appropriate PHP version.*

As such, SquareOne Global provides the `so composer` command that mirrors the native `composer` command.
The only difference is that it runs *inside* the docker php-fpm container.

## Production Builds

Production builds are not committed to the repository and are handled as part of deployments through CI tools. The commands run during a production deployment are:

```shell
composer install --optimize-autoloader --ignore-platform-reqs --no-dev
nvm use && yarn install
gulp server_dist
```
