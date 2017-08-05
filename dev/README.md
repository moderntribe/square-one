# Dev Setup

This project uses Node.js, npm, Grunt and Bower for task and package management.
___

**All cli commands found below should be executed at the root of this dev directory, NOT the project root.**
___

## Table of Contents

* System Requirements
	* Installation
	* Troubleshooting
* Noding
* Grunting
	* Getting Started
	* Grunt Task Dependencies
	* Running Grunt Tasks
* Bowering

## System Requirements

To build with [1], you must have the following installed on your system:

* [Node](http://nodejs.org/)
* [Grunt](http://gruntjs.com/getting-started)
* [Bower](http://bower.io/)
* [Sass](http://sass-lang.com/install)

### Installation

These tools need only to be installed globally, so if you already have them installed on your system, you are ready to use or add to the toolset.

You may refer to the respective links above for installation instructions for each, but below is a brief overview for each as well:

#### Node

Visit Node's website to download the [installer](http://nodejs.org/).

#### Grunt

The only requirement for Grunt is Grunts CLI(Command Line Utility).
If you don't already have that installed, install globally, this is not project specific.
In NPM we do so with the -g flag.

	npm install -g grunt-cli

#### Bower

[1] Only only requires that Bower by installed globally:

	npm install -g bower

#### Sass

Sass requires Ruby - if you're on OS X or Linux you probably already have Ruby installed; test with `ruby -v` in your terminal. On windows you can get the Ruby installer here [Ruby Installer](http://rubyinstaller.org/)

Once you have Ruby installed, you can install Sass like so:

	gem install sass

#### Compass

To install compass, run `gem install compass`

## Troubleshooting

* If you have trouble installing any of the tools, you may need to use the `sudo` command.
* If you run into any issues with some of the tasks run, npm rebuild and try again.
	`npm rebuild`
* Also make sure that git is installed globally as some bower packages require it to be fetched and installed.
For grunt specific dependencies, such as compass or sass, check the grunt task dependencies area below.


# Noding

npm is the official package manager for Node.js.
npm runs through the command line and manages dependencies for an application/build process.
We use it here to install grunt and its tasks.

Some nice tips on working with npm [here](http://howtonode.org/introduction-to-npm).

## The Package JSON

npm modules are registered in `dev/package.json` as "dependencies" in this case.
Outside of that object you will note something like this:

	"name": "tribe-build",
	"version": "1.0.0",
	"_bowerpath": "dev/bower_components",
	"_themepath": "content/themes/tribe-square-one",
	"_component_path": "dev/dev_components",
	"engines"       : {
	"node": "0.10.25",
	"npm" : "1.3.26"

Of note here are the keys that begin with an underscore. These are variables for our use in the packages, in this case Grunt tasks generally.
You can add more as you need for new directories or other uses.
For example, we can use them in Grunt tasks like so:

	libs: {
    		src: [
    			'<%= pkg._bowerpath %>/debug/ba-debug.js',
    			'<%= pkg._bowerpath %>/jquery-ui/ui/jquery.ui.core.js',
    			'<%= pkg._bowerpath %>/jquery-ui/ui/jquery.ui.effect.js',
    			'<%= pkg._bowerpath %>/jquery.fitvids/jquery.fitvids.js'
    		],
    		dest: '<%= pkg._themepath %>/js/libs.js'
    	},

# Grunting

##  Getting Started

Here are some helpful links to help you get started with using Grunt:

* [Grunt for People Who Think Things Like Grunt are Weird and Hard](http://24ways.org/2013/grunt-is-not-weird-and-hard/)
* [Grunt basics](http://gruntjs.com/getting-started) Getting started with Grunt, if you have no idea what grunt is.
* [Using the grunt CLI](http://gruntjs.com/using-the-cli) The commands you can use in grunt cli.

##  Grunt Task Dependencies

The installed grunt tasks require these external dependencies. As you add new ones document them here.

#### Grunt Compass

Compass operates on a folder level. Because of this you don't specify any src/dest, but instead define the sassDir and cssDir options OR use a config.rb file. A config.rb file is used by default in `content/themes/tribe-square-one/config.rb`

##  Running Grunt Tasks

All current tasks are listed here. As you add a new task, document it here.

___

These tasks are to be run in terminal/command prompt **IN THE DEV DIRECTORY**.
___

We run tasks by starting with

	grunt

in the cli. Just using `grunt` will run the default task if we don't also specify a particular task by name following the command.

#### Example

Here we will run the global watch task (to do things like compile css when you change it, compile js when you change it etc).

**This will run every sub task outlined in the `watch.js` file.**

	grunt watch

Now lets run the watch task JUST on the theme css, because i'm only working there today.
(this is not necessary, watch is smart and only compiles what it needs to, this is just an example)

	grunt watch:themecss

So to review, `grunt` runs all, `grunt taskname` runs a task with all of its subtasks, and `grunt taskname:subtaskname` runs the specific subtask.

Refer to the watch task in grunt_options/watch.js to understand how the structure and these commands relate.

## Defined Grunt Tasks

If you add a new task to this project, document it here!

* `grunt watch:themecss` watches the theme scss folder for changes and uses compass to compile it.
* `grunt watch:themejs` watches the theme js folder for changes and runs a range of other tasks to compile the theme javascript.
* `grunt clean:theme` cleans temp files from the theme.
* `grunt compass:theme` compiles all theme scss to css.
* `grunt pixrem:theme` adds a pixel fallback to any rem value in the css.
* `grunt concat:themelibs` joins all 3rd party libraries, generally from the bower_components folder and outputs a libs.js file in the theme js folder.
* `grunt concat:themescripts` joins all scripts in the theme/js/scripts folder and generates a scripts.js in the the theme/js folder.
* `grunt copy:movelibs` Moves libraries that need modification or need to be loaded independently from the bower_components folder to the theme.
* `grunt compass:theme` compiles all theme scss to css.
* `grunt modernizr:theme` scans the theme for references to modernizr tests, downloads the lates dev version and then builds a custom production version based on what was used.
* `grunt preprocess:theme` strips all debug code from theme js.
* `grunt uglify:theme` minifies all theme js.

## Installed Grunt Task Libraries

Installed grunt plugins and their documentation links:

* [grunt-contrib-copy](https://npmjs.org/package/grunt-contrib-copy) Copy files.
* [grunt-contrib-concat](https://npmjs.org/package/grunt-contrib-concat) Concatenate files.
* [grunt-contrib-jshint](https://npmjs.org/package/grunt-contrib-jshint) Validate files with JSHint.
* [grunt-contrib-watch](https://npmjs.org/package/grunt-contrib-watch) Run predefined tasks whenever watched file patterns are added, changed or deleted.
* [grunt-contrib-clean](https://npmjs.org/package/grunt-contrib-clean) Clean files and folders.
* [grunt-contrib-uglify](https://npmjs.org/package/grunt-contrib-uglify) Minify files with UglifyJS.
* [grunt-contrib-compass](https://npmjs.org/package/grunt-contrib-compass) Compile Sass to CSS using Compass
* [grunt remfallback](https://www.npmjs.org/package/grunt-remfallback) Finds rem values in CSS and creates fallbacks with px values.
* [grunt-newer](https://npmjs.org/package/grunt-newer) Run Grunt tasks with only those source files modified since the last successful run.
* [grunt-exec](https://npmjs.org/package/grunt-exec) Grunt task for executing shell commands.
* [grunt-modernizr](https://npmjs.org/package/grunt-modernizr) Create production version of modernizr based on what is used in the project.
* [grunt-preprocess](https://npmjs.org/package/grunt-preprocess) Preprocess HTML, JavaScript etc directives based off environment configuration.

##  Adding a New Grunt Task

You'll find them in grunts repolist, npm and on github.
Search here first: [Grunt Plugins Directory](http://gruntjs.com/plugins)

Don't follow all of a tasks instructions for install. :P We've changed things a bit, but dont't worry, these changes actually make it easier for you.

When installing a new package, make sure you add the flag `--save-dev` to ensure that it will be automatically added to the package.json file.

### Example

In this example, we will install [grunt-auto-prefixer](https://www.npmjs.org/package/grunt-autoprefixer). This task prases css and adds vendor-prefixed css properties using the Can I Use database.

We will run the install as they define, but we will add the flag `--save-dev`:

	npm install grunt-autoprefixer --save-dev

We do not need to  add the load task line to the gruntfile, our system autoloads. **Eg a line like this is not needed in [1]:**

	grunt.loadNpmTasks('grunt-autoprefixer');



To define your tasks, don't add them to the gruntfile.js; Instead, **create a new js file in the `dev/grunt_options` folder with a filename of the task**.

In this case its task name is `autoprefixer` so we make a file inside the grunt_options folder called "autoprefixer.js".
Inside that file we wrap our task in this:

	module.exports = {

		// tasks here

	};

Check the existing files in /grunt_options/ for reference. Also, please note the existing comments in each task file and emulate. In this case, let's set our target as `theme` and set the src and dest paths:

	module.exports = {

	    theme: {
		     src: '<%= pkg._themepath %>/css/master.css',
		     dest: '<%= pkg._themepath %>/css/master.css'
	    }

	};

Your last step is to register your task, if you need to.
Your task can be called without registering with `grunt taskname` but you can also add it to a build flow, and probably will want to.
This is done at the bottom of the `gruntfile.js`.
Here is an example of a task that executes a range of subtasks relating to our theme.

	grunt.registerTask(
		'theme', [
			'copy:movelibs',
			'concat:themelibs',
			'concat:themescripts',
			'preprocess:theme',
			'uglify:theme',
			'clean:theme',
			'modernizr:theme',
			'compass:theme',
			'remfallback:theme'
		]);


We can now add `autoprefixer:theme` to the bottom of that list:

	grunt.registerTask(
		'theme', [
			'copy:movelibs',
			'concat:themelibs',
			'concat:themescripts',
			'preprocess:theme',
			'uglify:theme',
			'clean:theme',
			'modernizr:theme',
			'compass:theme',
			'remfallback:theme',
			'autoprefixer:theme'
		]);

We can run that group with `grunt theme`.

# Bowering

##  Bower (Package manager)

Dev versions of jquery, jquery ui and modernizr are included. Any other components should be installed as needed.

[Bower Documentation](http://bower.io/)

## Usage

Much more information is available via `bower help` once it's installed.

### Example

In this example lets grab a development version of moment.js (a time manipulation library for javascript) and then incorporate it in our themes library file.

In checking the Bower search we see that indeed moment is registered. [http://bower.io/search/?q=moment](http://bower.io/search/?q=moment)
Heading over to Moments site we see a few Bower install options [http://momentjs.com/docs/](http://momentjs.com/docs/)
Lets install the dev version with

	bower install -S momentjs

Important note: Always use  the `-S` flag when installing bower packages. That adds the dependency to the bower.json file.

After install has completed, check out your dev/bower_components folder to see the moment folder that's been added.
At this point, run a commit. As a rule, commit each dep or npm install on its own.
Now you want to do something with this lib. In this case its a library we need in our theme.
So we'll head over to our concat.js file in dev/grunt_options and check out the `themelibs` task.
We'll want to add moment to our src portion of this task, so like yo:

	'<%= pkg._bowerpath %>/momentjs/moment.js',

Restart your cli if already open and do a

	grunt theme

And voila, it will get mixed in to the libs and libs.min file.

You can also install directly from github, read more here:

[https://github.com/bower/bower#usage](https://github.com/bower/bower#usage)

# Misc

## Checking in Node Modules

Why commit the node modules to the git repo, especially with nodes shrinkwrap feature in place?

* Bower still doesnt have shrinkwrap as of Feb 2014.
* Longevity and module/package history.
* We know the build process wont break in 6 months or 6 years. Probably. :P