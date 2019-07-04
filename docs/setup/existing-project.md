# Getting Started: New SquareOne Project

Note: This guide is meant to help get SquareOne running. It does not cover the full breadth of setting up a project at Modern Tribe; please see your lead for more information.

#Installation
* [Server Requirements](#server-requirments)
* [Development Requirements](#development-requirments)
* [SquareOne Local](#squareone-local)
* [Installing SquareOne](#Installing an existing SquareOne Project)
* [Configuration](#configuration)

## Server Requirements <a name="server-requirments"></a>
SquareOne has the same baseline requirements as WordPress, with some exceptions. Note, all these requirements are met be the SquareOne Local setup, so it's highly recommended you use SquareOne Local as you development environment.

However, if prefer to not use SquareOne Local, you will need to make sure your dev setup meets the following requirements:

HTTP Server
* Nginx or Apache
* PHP 7+, 7.2 recommended
* TODO PHP Packages?

SQL Server
* MySql 5.7+ or MariaDB 10.1+, latest recommended

Redis Server
* Latest recommended

## Development Requirements <a name="development-requirments"></a>
* Git
* Composer
* Node (8.9.3)
* Yarn
* Gulp
* Docker

## Installing an existing SquareOne Project <a name="squareone-local"></a>

There should be an existing repository for the project that is based off square one. Get access to that repository from your project lead.

#### Clone Project

Clone a copy of the project into a working root foldr. `git clone git@github.com:moderntribe/project-name.git .`

#### Configure Dev Domain & SSL

1. Lookup dev domain that is configured in the `/dev/docker/docker-compose.yml` set as `VIRTUAL_HOST=`. 
2. Configure SSL: Run `npm run docker:global:cert DOMAIN.tribe`, where `DOMAIN` is the dev domain.

#### Spin up containers.

SquareOne Local is broken into two parts, the Global Containers (DNS, SSL, SQL management for all SquareOne Projects) and the Local containers for this specific project (HTTP, Cache, Search, etc.). 

Note, local containers will NOT work if the Global containers are not running.

1. Run `npm run docker:start:global` (Turns on the global containers. If you are running multiple projects, this only needs to be run once)
2. Run `npm run docker:start`
3. Navigate to the selected dev domain. `https://DOMAIN.tribe`. 

## Build SquareOne

When you spin up the containers for the first time, much of some of build process is completed for you. If for any reason you need to run them again, here are the commands:

1. Composer: Run `bash ./dev/docker/composer.sh`. This will run inside the php container. You can run composer on the host machine, but you need to make sure you have the proper dependencies.
2. NPM: Run `yarn install`. This will install any node dependencies.
3. Gulp: Run `gulp`. This will build the CSS/JS bundles.

## Configuration <a name="configuration"></a>

#### Create a Database

1. Using a MySQL client, connect to `mysql.tribe` with user `root` and password `password`
2. Create a db of your naming

#### Configure WordPress

1. Create an `.env file`, then reference the `wp-config.php` to override any settings that reference `tribe_getenv()`
2. Copy the `local-config-sample.php` to `local-config.php` and add any configurations. 

NOTE: This doc does not cover multi-site or other WordPress configurations. Make sure you read all the configuration to ensure it's what you need for the project.  

#### Import Seed Database

If the team has an existing dev environment, you should import the seed data into your local as a starting point. If there is no DB, you may need to pull a backup from an existing environment. Contact your lead for help.

#### Success

If you can navigate to `https://DOMAIN.tribe` and see your site, huzzah! If you can't load the site or have any issues along the way, reference the [full docker setup documentation](/docs/docker/README.md) – there are some quirks depending on your local setup it covers in detail. If you are still stuck, ping a Lead for help.



