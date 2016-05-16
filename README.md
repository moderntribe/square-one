#  Tribe Square One ([1]) Development Framework


[1] is a development framework created by Modern Tribe for use in our WordPress projects. It contains a base for:

* Our build process (node, grunt, webpack)
* Theme independent core functionality plugin | /content/mu-plugins
* Core plugin | /content/plugins/core
* Core theme | /content/themes/core
* Front-end asset structure (JS, PostCSS & CSS)


## Getting Started


To begin using [1]:

Please refer to the documentation in the /docs folder.

### Setting up the Codeception test
If you are going to write and/or run the [Codeception](http://codeception.com/) tests then those will need some set up too.  
Read the [Codeception](http://codeception.com/) and [WP Browser](https://github.com/lucatume/wp-browser) documentation if stuck, the specific tool guides are there: this is a guide to the particular set up.  
The `tests_sample` folder contains the result of such a set up so use it as a guide.

1. While in the `dev` folder run the command
    
    codecept bootstrap
    
this will trigger the generation of the `dev/tests` folder and the `codeception.yml` file. That file will contain the settings Codeception will use to run and, unless some particular configuration is required on a suite base, is the place to set any and all the modules that will be used in the test suites; again look into possible settings in the `codeception.dist.yml` file.  
By default Codeception will generate 3 suites:

* unit - for unit tests
* functional - for services, chains of classes
* acceptance - for UI tests with an headless browser without JS enabled

To cover the missing testing possiblity offered by a JS enabled web driver generate the `jsAcceptance` suite; again look into the `dev/test_sample` folder for an example.

    codecept generate:suite jsAcceptance
    
the suite configuration file will appear and the folder with it. JS enabled tests will "drive" a web browser like Selenium or Chrome, or (faster) [PhantomJS](http://phantomjs.org/).  
Again refer to [Codeception](http://codeception.com/) docs.  
Some tests are included in the `dev/test_sample` folder to offer examples.

#### Using the build tools

The build tools for themeing can be found in the *root* directory. Refer to the docs for instructions on getting started with Grunt, Webpack, and PostCSS.


## Configurations


*Production*

When using this on a production environment, be sure to add the following to your local-config.php:
    define( 'ENVIRONMENT', 'PRODUCTION' );

*Development*

GLOMAR is a plugin that blocks the frontend of the site from public access. If you would like to disable the plugin locally, add the following to your local-config.php:
    define( 'TRIBE_GLOMAR', false );


## Upgrade Notice

= 1.1 =
Moved the project dependencies to Composer  
Added Codeception tests examples and instructions  
= 1.0 =
Initial Release


## Changelog


= 1.1 =
Moved the project dependencies to Composer  
Added Codeception tests examples and instructions  
= 1.0 =
Initial Release

