# Build Process

## Quick Start

1. Required tools to be installed
   1. [nvm](https://github.com/creationix/nvm)
   1. [yarn](https://yarnpkg.com/en/docs/install).
   1. [gulp-cli](https://www.npmjs.com/package/gulp-cli)
1. Build frontend development assets
   1. `nvm use`
   1. `yarn install`
   1. `gulp dist`
1. Install backend development dependencies
   1. `composer install` or `so composer install` if using SquareOne Global Docker

> Note: Make sure your composer version matches the project requirements. As of Oct 2021, Composer v2.

## Building Frontend Assets

### Node

This system uses NPM for dependency management for front end assets.

Each SquareOne project tracks the version you should be using in the .nvmrc file. We recommend
the version manager [nvm](https://github.com/creationix/nvm) to manage your node versions. With this installed,
all you need to do is run `nvm use` and it will automatically switch to the correct version.

To install your dependencies SquareOne uses yarn: [install yarn](https://yarnpkg.com/en/docs/install).

Then simply `yarn install` in the root of this project.

### Gulp

This system uses gulp to run tasks.

If you don't already have the [gulp-cli](https://www.npmjs.com/package/gulp-cli) installed globally on that node version do so now.

During dev you have two choices for gulp tasks to run when editing any pcss or javascript files.

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
`composer install`

If using SquareOne Global Docker:
`so composer install`

> Note: Make sure your composer version matches the project requirements. As of Oct 2021, Composer v2.

## Production Builds

Production builds are not committed to the repository and are handled as part of deployments through CI tools. The commands run during a production deployment are:

```shell
composer install --optimize-autoloader --ignore-platform-reqs --no-dev
nvm use && yarn install
gulp server_dist
```
