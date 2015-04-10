#  Tribe Square One ([1]) Development Framework


[1] is a base development framework used by Modern Tribe when kicking off new WordPress projects. It contains a base for:

* Our build process (node, grunt & bower) | /dev
* Theme independent core functionality plugin | /content/mu-plugins
* Core plugins (used on every project) | /content/plugins
* Theme (templates, markup & structure) | /content/themes/tribe-square-one
* Front-end asset structure (JS, SCSS & CSS)


## Getting Started


To begin using [1]:

1. Change the name of our WordPress theme from *tribe-square-one* to *tribe-clientname*
2. Update the information in: *content/themes/tribe-clientname/style.css*
3. Update the constant: *WP_DEFAULT_THEME* in *wp-config.php* to the updated theme name
4. Update the variable *_themepath* found in: */dev/package.json* to be: *content/themes/tribe-clientname*

### Setting up the Codeception test
If you are going to write and/or run the [Codeception](http://codeception.com/) tests then those will need some set up too.  
Read the [Codeception](http://codeception.com/) and [WP Browser](https://github.com/lucatume/wp-browser) documentation if stuck, the specific tool guides are there: this is a guide to the particular set up.  
The `tests_sample` folder contains the result of such a set up so use it as a guide.

1. While in the `dev` folder run the command
    
    codecept bootstrap
    
this will trigger the generation of the `dev/tests` folder and the `codeception.yml` file. That file will contain the settings Codeception will use to run and, unless some particular configuration is required on a suite base, is the place to set any and all the modules that will be used in the test suites; again look into possible settings in the `codeception-sample.yml` file.  
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

The build tools for themeing can be found in the */dev/* directory. Refer to its README for instructions on getting started with Grunt, Bower, and SASS.

*NOTE: One helpful tip, all relevant directories will contain a readme outlining the directory contents as well as any relevant information, tips, and instructions.*


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

