# Getting Started: New SquareOne Project

Note: This guide is meant to help get SquareOne running. It does not cover the full breadth of setting up a project at Modern Tribe; please see your lead for more information.

## Installation
* [Server Requirements](#server-requirments)
* [Development Requirements](#development-requirments)
* [Installing SquareOne](#installing-squareone)
* [SquareOne Local](#squareone-local)
* [Configuration](#configuration)

## Server Requirements
SquareOne has the same baseline requirements as WordPress, with some exceptions. Note, all these requirements are met by the SquareOne Local setup, so it's highly recommended you use SquareOne Local as you development environment.

However, if prefer to not use SquareOne Local, you will need to make sure your dev setup meets the following requirements:

HTTP Server
* Nginx or Apache
* PHP 7+, 7.2 recommended
* TODO PHP Packages?

SQL Server
* MySql 5.7+ or MariaDB 10.1+, latest recommended

Redis Server
* Latest recommended

## Development Requirements
* Git
* Composer
* Node (8.9.3)
* Yarn
* Gulp
* Docker

## Installing SquareOne

SquareOne is hosted on github and should be COPIED, not cloned, into your project folder. Then create a new repository for your project.

#### Copy SquareOne 

[Download a copy of SquareOne](https://github.com/moderntribe/square-one/archive/master.zip) and place it in your project root.

#### Environment Install Variables

SquareOne uses .env files for managing secrets. You can see an `.env.sample` file in the root. Before installation some keys are required.

1. Copy the `.env.sample` file and name it `.env`
2. Fill in the two required plugins keys

Notes
* Never commit a .env file to the repository. They are only for storing env secrets and should not be saved to the codebase.
* Some licensed plugins are required for SquareOne projects (ACF, GravityForms). Removing them is possible, but is not covered here.

#### Version Control Setup

1. Create a new target repository.
2. Run `git init && git add -A && git commit -m "initial commit"` in your root folder
3. Push the repo to remote repository. If you're using Github run this with your proper user/project-name `git remote add origin git@github.com:moderntribe/project-name`. 
4. Run `git push -u origin master`

#### Setup project branches

We use a GitOps flow for our deployments. Create the required branches below and push them.

```
git branch server/dev
git branch server/staging
git branch server/production
```

Note(s):
* Code flows from Dev>Staging>Production only. 

## SquareOne Local

SquareOne Local is a Docker based development environment and is broken into two parts,  Global Containers (DNS, SSL, SQL management for all SquareOne Projects) and the Local containers for this specific project (HTTP, Cache, Search, etc.). We've built in everything you need for a distributable development environment. 

Notes: 
* Local containers will NOT work if the Global containers are not running.
* It's recommended you clone a second copy of SquareOne and run and manage your Global containers from there.
* You can start the global containers from ANY squareOne install, but it's recommended to have a separate squareOne installation for starting/stopping the global containers. This way your SSL certs are all installed in the same installation.

#### Configure Dev Domain & SSL

1. Setup dev domain: Choose a `.tribe` dev domain like `mysite.tribe` Update `VIRTUAL_HOST` this domain in the `/dev/docker/docker-compose.yml`.
2. Configure SSL: Run `npm run docker:global:cert mysite.tribe`
3. Copy `local-config-sample.json` to `local-config.json` and set the `proxy` value to your domain 

Notes:
* As noted before, its recommended to have a "global" install of SquareOne and installing every projects certs there. Otherwise, you'll need to start and stop the global containers from each individual project.

#### Spin up containers

1. Run `npm run docker:global:start` (Turns on the global containers. If you are running multiple projects, this only needs to be run once)
2. Run `npm run docker:start` (Turns on the local project containers. Recommended you start and stop as needed)

Notes: 
* Local containers will NOT work if the Global containers are not running. 

## Build SquareOne

When you spin up the containers for the first time, some of the build process is completed for you. If for any reason you need to run them again, here are the commands:

1. Composer: Run `bash ./dev/docker/composer.sh`. This will run inside the php container. You can run composer on the host machine, but you need to make sure you have the proper dependencies.
2. NPM: Run `yarn install`. This will install any node dependencies.
3. Gulp: Run `gulp`. This will build the CSS/JS bundles.

## Configuration

#### Create a Database

1. Using a MySQL client, connect to `mysql.tribe` with user `root` and password `password`
2. Create a db of your naming
3. Go add these credentials to the `.env` file

#### Configure WordPress

By default, everything will work at this point, but you might want to tweak the wordpress config like turning on multisite. Look at the `wp-config.php` and you'll see it's not standard. It's a bit overwhelming actually, but it's where all the configuration is initiated. You'll see that it parses the .env file, loads a local-config file, and checks for any defined constants. So you have your choice of how to override the defaults in two places:

1. Add a config constant to the `.env` file
2. Copy the `local-config-sample.php` to `local-config.php`, and add your configuration there.

NOTE:
* IF you are configuring secrets, the `.env` file is the best place.
* IF you need to add custom configuration, the `local-config.php` is the best place.
* IF you need to share configuration, you can modify the `wp-config.php` directly.

#### Success

If you can navigate to `https://DOMAIN.tribe` and see your site, huzzah! Next steps, work with your team to make sure all the proper configuration is in place. 

If you can't load the site or have any issues along the way, reference the [full docker setup documentation](/docs/docker/README.md) – there are some quirks depending on your local setup it covers in detail. If you are still stuck, ping a Lead for help.



