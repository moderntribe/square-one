# Installation

SquareOne has the same baseline [requirements as WordPress](https://wordpress.org/about/requirements/) with some additional requirements.

> Note, all these requirements are met by the [SquareOne Global Docker](https://github.com/moderntribe/square1-global-docker) setup, so it's highly recommended you use SquareOne Global Docker as your development environment.

## Server Requirements

#### HTTP Server
* Nginx or Apache
* PHP 7.4+
* HTTPS Support

#### SQL Server
* MySql 5.7+ or MariaDB 10.1+, latest recommended

#### Memcached Server
* Latest recommended

## Development Requirements

* Git

#### PHP
* Composer

#### Nodejs
* Node Version Manager (nvm)
* Node
* Yarn
* Gulp

## Included Development Environment, SquareOne Global Docker
[SquareOne Global Docker](https://github.com/moderntribe/square1-global-docker) is a Docker CLI tool and configuration. The CLI tool will orchestrate the local development environment. It runs two sets of containers, Global Containers (DNS, SSL, SQL management for all SquareOne Projects) and the Local containers for this specific project (HTTP, Cache, Search, etc.). We've built in everything you need for a distributable development environment.

## Creating a *NEW* SquareOne Project with SquareOne Global Docker

1. [Install Docker for your platform](https://www.docker.com/get-started)
1. [Install SquareOne Global Docker CLI](https://github.com/moderntribe/square1-global-docker#installation)
1. Create a new empty repository for your project.
1. run `so create` in a new folder. This will create a brand new Sq1 Project and provide prompts for configuration.
1. run `so start`.
1. [Build SquareOne](docs/getting-started/02_build.md)
1. That's it!

## Installing an *EXISTING* SquareOne Project with SquareOne Global Docker

### Install SquareOne Global Docker

1. [Install Docker for your platform](https://www.docker.com/get-started)
1. [Install SquareOne Global Docker CLI](https://github.com/moderntribe/square1-global-docker#installation)
1. Clone a copy of the project into a working root folder. `git clone git@github.com:moderntribe/project-name.git .`
1. Run `so boostrap` or `so boostrap --multisite`. This will create a DB and automatically configure SquareOne.
1. Ask for an existing DB from a colleague or get a production export. 
1. Using a MySQL client, connect to `mysql.tribe` with user `root` and password `password`.
1. Import the DB to the `tribe_projectid` database.
1. Run `so migrate-domain`.
1. [Build SquareOne](docs/getting-started/02_build.md)   
1. That's it!

## SquareOne with an Alternative Local Development Environment

As noted, SquareOne in all intents and purposes, is WordPress. So it can be run on any local development environment 
that supports WordPress assuming you address these two additional requirements:

1. WordPress is installed in a sub-folder as a dependency. We've bundled a custom Nginx file that handles this nicely here `dev/docker/nginx/nginx.conf`.
2. All configuration and build steps must be completed.

### LocalWP
For those who want to use [LocalWP](https://localwp.com/), a popular local development environment, we provide a plugin that handles the Nginx config for you [here](https://github.com/moderntribe/square-one-localwp-addon).

## Manually Setup and Configure SquareOne

### Clone Project

Clone a copy of the project into a working root folder. `git clone git@github.com:moderntribe/project-name.git .`

### Environment Install Variables

SquareOne uses .env files for managing secrets required at build time. You can see an `.env.sample` file in the root. Before installation, 
some secrets are required.

1. Copy the `.env.sample` file and name it `.env`
2. Fill in the required secrets from [1Password](https://start.1password.com/open/i?a=MTSABMIBDJF4PHQCMXYNWKAL4U&v=k2qbci5enqpfc4am7uvlwt6w4m&i=v67do7z62rd5nb7nrfkeih2uxa&h=moderntribe.1password.com).

> NOTE:
> * Never commit a .env file to the repository. They are only for storing env secrets and should not be saved to the codebase.
> * Some licensed plugins are required for SquareOne projects (specifically ACF & GravityForms). Removing them is possible, but is not covered here.

## Build SquareOne

1. `composer install` - This builds the PHP app and installs WordPress and plugin. (Note, if you are using `so` you can run `so composer install`)
2. `nvm use && yarn install` - This will change to the correct node version and install node dependencies.
3. `gulp` - This will build the CSS/JS bundles for the app.

> NOTE: [See more complete build documentation here.](docs/getting-started/02_build.md) 

## Configuration

### Configure SquareOne (WordPress)

Look at the `wp-config.php` and you'll see it's not standard. In addition, look at the `wp-config-enviroment.php` file. This is where we handle a lot of the configuration for you.
Any existing project all the defaults will be added to the `wp-config-enviroment.php` file. Then on any environment, you can set or override any setting in the `local-config.php`. 
The `local-config.php` is not tracked in git, so it will be generated for each environment specifically based off the `local-config-sample.php`.

1. Copy the `local-config-sample.php` to `local-config.php`, and add your configuration there.

> NOTE:
>* IF you are configuring secrets only needed at build time, the `.env` file is the best place.
>* IF you need to add custom configuration or environment secrets, the `local-config.php` is the best place.
>* IF you need to share configuration, you can modify the `wp-config-enviroment.php` and commit.

### Choose a local dev toolset

If you are not using [SquareOne Global Docker](https://github.com/moderntribe/square1-global-docker), 
you need some other Local Development Environment that is WordPress compatible and can meet SquareOne requirments.

### Create a Database

1. Using a MySQL client, connect to your local mysql
2. Create a db of your naming
3. Add the standard WordPress database credentials to the `local-config.php`

### Import Seed Database

If the team has an existing dev environment, you should import a database into your local as a starting point. If there is no DB yet, follow the standard WordPress install process.

### That's all!


