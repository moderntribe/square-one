# Installation

SquareOne has the same baseline [requirements as WordPress](https://wordpress.org/about/requirements/) with some additional requirements.

> Note, all these requirements are met by the [SquareOne Global Docker](https://github.com/moderntribe/square1-global-docker) setup, so it's highly recommended you use SquareOne Global Docker as your development environment.

## Server Requirements

HTTP Server
* Nginx or Apache
* PHP 7.4+ recommended
* HTTPS Support

SQL Server
* MySql 5.7+ or MariaDB 10.1+, latest recommended

Memcached Server
* Latest recommended

## Development Requirements
* Git
* Composer v2
* Node Version Manager (nvm)
* Node
* Yarn
* Gulp
* Docker

## Installing an existing SquareOne Project with SquareOne Global Docker (Recommended)

[SquareOne Global Docker](https://github.com/moderntribe/square1-global-docker) is a Docker CLI tool and configuration. The CLI tool will orchestrate the local development environment. It runs two sets of containers, Global Containers (DNS, SSL, SQL management for all SquareOne Projects) and the Local containers for this specific project (HTTP, Cache, Search, etc.). We've built in everything you need for a distributable development environment.

### Install SquareOne Global Docker

Copy the following in your terminal:

bash -c "$(curl -fsSL https://raw.githubusercontent.com/moderntribe/square1-global-docker/master/install/install.sh)"

> Note for macOS users: This script will install brew and all the requirements listed above.
> Note for Debian users: This script will install and configure all the required packages.

### Clone Project

Clone a copy of the project into a working root folder. `git clone git@github.com:moderntribe/project-name.git .`

### Environment Install Variables

SquareOne uses .env files for managing secrets required at build time. You can see an `.env.sample` file in the root. Before installation some secrets are required.

1. Copy the `.env.sample` file and name it `.env`
2. Fill in the required secrets

Notes
* Never commit a .env file to the repository. They are only for storing env secrets and should not be saved to the codebase.
* Some licensed plugins are required for SquareOne projects (ACF, GravityForms). Removing them is possible, but is not covered here.

### Start the Project

1. Goto any squareone project roo and run `so start`

Notes:
* This command will automatically start the Global and Local project containers. For other commands, please see the [SquareOne Global Docker](https://github.com/moderntribe/square1-global-docker) documentation.

## Build SquareOne

When you spin up the containers for the first time, some of the build process are completed for you. If for any reason you need to run them again, here are the commands:

1. `composer install` - This builds the PHP app and installs WordPress and plugin. (Note, if you are using `so` you can run `so composer install`)
2. `nvm use && yarn install` - This will change to the correct node version and install node dependencies.
3. `gulp` - This will build the CSS/JS bundles for the app.

## Configuration

### Configure SquareOne (WordPress)

Look at the `wp-config.php` and you'll see it's not standard. In addition, look at the `wp-config-enviroment.php` file. This is where we handle a lot of the configuration for you.
Any existing project all the defaults will be added to the `wp-config-enviroment.php` file. Then on any environment, you can set or override any setting in the local-config.php

1. Copy the `local-config-sample.php` to `local-config.php`, and add your configuration there.

> NOTE:
>* IF you are configuring secrets only needed at build time the `.env` file is the best place.
>* IF you need to add custom configuration, the `local-config.php` is the best place.
>* IF you need to share configuration, you can modify the `wp-config-enviroment.php` and commit.

### Create a Database

1. Using a MySQL client, connect to `mysql.tribe` with user `root` and password `password`
2. Create a db of your naming
3. Add credentials to the `local-config.php`

### Import Seed Database

If the team has an existing dev environment, you should import the seed data into your local as a starting point. If there is no DB yet, follow the standard WordPress install process.

### Success

If you can navigate to `https://DOMAIN.tribe` and see your site, huzzah! Next steps, work with your team to make sure all the proper configuration is in place.

## SquareOne with an alternative local dev environment

As noted, SquareOne is all intents and purposes, WordPress. So it can be run on any local dev environment that supports WordPress, with a few tweaks:

1. WordPress is installed in a sub-folder as a dependency. We've bundled a custm Nginx file that handles this nicely here `dev/docker/nginx/nginx.conf`.
2. All build steps are still required for the Application to work, documented above under the "Build SquareOne" header.

### LocalWP
For those who want to use LocalWP, another popular locla dev env, we provide a plugin that handles the Nginx config for you [here](https://github.com/moderntribe/square-one-localwp-addon). 
