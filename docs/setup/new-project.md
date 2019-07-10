# Getting Started: New SquareOne Project

Note: This guide is meant to help get SquareOne running. It does not cover the full breadth of setting up a project at Modern Tribe; please see your lead for more information.

## Installation
* [Server Requirements](#server-requirments)
* [Development Requirements](#development-requirments)
* [Installing SquareOne](#installing-squareone)
* [SquareOne Local](#squareone-local)
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

## Installing SquareOne <a name="installing-squareone"></a>

SquareOne is hosted on github and should be COPIED, not cloned, into your project folder. Then create a new repository for your project.

#### Copy SquareOne 

[Download a copy of SquareOne](https://github.com/moderntribe/square-one/archive/master.zip) and place it in your project root.

### Environment Variables

SquareOne uses .env files for managing secrets. You can see an `.env.sample` file in the root. 

1. Copy the `.env.sample` file and name it `.env`
2. Notice we have various environmental vars that are required. Fill them in.


Notes
* Never commit a .env file to the repository. They are only for storing env secrets and should not be saved to the codebase.
* Some licensed plugins are required for SquareOne projects (ACF, GravityForms). Removing them is possible, but is not covered here.

#### Version Control Setup

1. Create a new target repository.
2. Run `git init && git add -A && git commit -m "initial commit"` in your root folder
3. Push the repo to remote repository. If you're using Github like so `git remote add origin git@github.com:moderntribe/project-name`. 
4. Run `git push -u origin master`

#### Setup project branches

We use a GitOps flow for our deployments. Create the required branches below and push them.

```
git branch server/dev
git branch server/staging
git branch server/production
```

## SquareOne Local <a name="squareone-local"></a>

SquareOne Local is broken into two parts, the Global Containers (DNS, SSL, SQL management for all SquareOne Projects) and the Local containers for this specific project (HTTP, Cache, Search, etc.). We've build in everything you need for a distributable development environment. 

Notes: 
* Local containers will NOT work if the Global containers are not running.
* You can start the global containers from ANY squareOne install, but it's recommended to have a separate squareOne installation for starting/stopping the global containers. This way your SSL certs are all installed in the same installation.

#### Configure Dev Domain & SSL

1. Setup dev domain: Choose a `.tribe` dev domain like `mysite.tribe` Update `VIRTUAL_HOST` this domain in the `/dev/docker/docker-compose.yml`.
2. Configure SSL: Run `npm run docker:global:cert mysite.tribe`


#### Spin up containers

1. Run `npm run docker:global:start` (Turns on the global containers. If you are running multiple projects, this only needs to be run once)
2. Run `npm run docker:start` (Turns on the local project containers. Recommended you start and stop as needed)
3. Navigate to the selected dev domain. `https://mysite.tribe`. 

## Build SquareOne

When you spin up the local containers for the first time, the build process is automatically run for you. If for any reason you need to run them again, here are the commands:

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

#### Success

If you can navigate to `https://mysite.tribe` and see your site, huzzah! If you can't load the site or have any issues along the way, reference the [full docker setup documentation](/docs/docker/README.md) – there are some quirks depending on your local setup it covers in detail. If you are still stuck, ping a Lead for help.



