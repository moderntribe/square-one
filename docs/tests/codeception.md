# Setting up the Codeception test

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

##Table of Contents

* [Overview](/docs/tests/README.md)
* [Codeception](/docs/tests/codeception.md)
* [Jest](/docs/tests/jest.md)
