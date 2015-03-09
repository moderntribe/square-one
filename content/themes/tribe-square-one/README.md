#  === [1] Theme ===


Welcome to [1]! Our theme is setup with a base for templates, markup, functions, CSS & SCSS, and JS, including overall organization and structure as well.

The following lays out specific thoughts regarding the various aspects of [1].


##  === Ground Rules ===


* Let's start with the easiest first: write good, clean, well structured and commented code!
* Be nice and comment your code. You should get a good idea of guidelines for doing so within the various files and languages in [1].
* If you have questions about anything at all always ask!
* Do your best to stick to the conventions and rules laid out in [1], and if you have ideas to improve upon [1] let us know.


##  === CSS & SCSS ===


[1] contains base SCSS & CSS for all templates including base mixins, functions, and a grid.

[1] also utilizes:

* CSS3
* SCSS + Compass as our CSS preprocessor of choice

A config.rb file has also been provided so you can utilize a processor such as Codekit, but we do recommend that you stick with the automated build process using grunt watch.

*Ground Rules*

With the power of SCSS comes great responsibility. The responsibility to write good, lean, smart SCSS & CSS. Here are some ground rules to keep in mind which will help us all better do this.

* Follow the inception rule, only nest up to four levels deep: [http://thesassway.com/beginner/the-inception-rule]http://thesassway.com/beginner/the-inception-rule
* Keep your SCSS neatly organized and commented
* In an attempt to help clean up our markup a bit, unless needed, try to use SCSS placeholders and extends to avoid unecessary classes or IDs
* To keep our SCSS well organized and quick to maintain, we are attempting to keep any specific fallbacks (IE, modernizr, etc), media queries, retina / HDPI images inline with it's relevant module or block of SCSS, see example below (which is nicely formatted, commented, and makes use of some of our magical mixins for media queries, modernizr, and IE8):

// Layout: global content wrap
.content-wrap {
	// MQ
	@include mq(full) {
	
	}
	// NO MQ or IE8
	@include no-mq {
	
	}
	// Retina & HDPI
	@include mq(retina) {

	}
}


##  === JS ===

# === Javascript ===

All javascript for the [1] theme is found in the /js folder of this theme directory.
All files at the root of this folder should not be edited directly, they are compiled.
They consist of 4 files, in both a development and minified state, that load sitewide.
Control of whether or not minified files are loaded on the front end is determined by the wordpress SCRIPT_DEBUG constant
in your local-config.php. Check the project root readme for more information.

Each file is discussed below, in the order they are loaded in the site. Then the development directories and methods are explained.

##  === modernizr.js ===

This file is loaded in the head of the site, on every page, and you probably know what it does. ;)
This version is compiled automatically by grunt. The scss and js files in the js/scripts folder are scanned
for modernizr test references and those that are detected are compiled into the production version.
The .dev version contains all tests and is loaded when SCRIPT_DEBUG is true.

##  === libs.js ===

All third party libraries used by our custom scripts are concatenated into this file, which loads in the footer of the site.
These libraries are managed with bower in the /dev folder and live in /dev/bower_components.
Add new components in the /dev/grunt_options/concat.js file for inclusion. Check there to see what tools are already available.

##  === templates.js ===

This file loads after libs and contains all precompiled handlebars templates used in the site.
The grunt theme watch process automatically compiles these from the themes js/templates folder.
It injects these into the themes custom javascript namespace, "modern_tribe" in the "templates" object.
Usage and notes are described later.

##  === scripts.js ===

This file contains all custom front end js modules wrapped in the "modern_tribe" javascript namespace by grunt.
Check the /dev/grunt_options/concat.js scripts task to see what the wrapper contains and how it aliases various globals.

##  === Handelbars Templates ===

As said Handlebars templates are precompiled by grunt as you work on them if the grunt watch task is running.
Their location is /js/templates. Note the partials folder.
Handlebars helpers are added in the js/helpers folder and will be automatically added at the end of the libs file by grunt.
Accessing them from a module in the scripts folder is done like this (in the case of the js/templates/tooltips/saved_items.handlebars template):

	 template = t.templates['tooltips/saved_items'];

##  === js/scripts ===

This folder is were all of our custom js is contained.
Currently it contains a main root level, and then a subdirectory enabled /modules sub tree.
Root contains modules that are basically global config items.
Tests, utilities, custom jquery plugins, sitewide state and data objects live in the core and util folders...

* browser.js: dont use it. :P (generally tries to avoid user agent sniff at least)
* data.js: nothing to see here. but if some data needs to be stored consistently, do so here.
* elements.js: consistent dom elements cached as jquery objects and used by multiple modules.
* events.js: global events. currently just a debounced resize event and the window load event. All other events are stored in their modules.
* init.js kick off.
* options.js sitewide options object
* plugins.js custom jquery functions used by modules.
* state.js sitewide state used by the modules and root.
* tests.js store widely used tests here.

The modules folder is were we store groups of functionality in organized pieces.
Create a subdirectory and sub namespace as applicable. Try to sync with php/scss names were possible/applicable.
New files are automatically baked in by grunt concat, no need to worry about order or adding them in the task.
They really are just namespaced wrappers, do whatever you need in them.
Filenames are not used for namespacing, just try to sync them with php/scss template names if it fits.
You will see structure like this a lot, but do what you need, as long as you probably hook up an init in functions.js on ready or load. ;)

	s.some_name = {

		I_AM_CONSTANT: 100

		// modules cached els, use getElementBy or querySelector or whatevs

		$el: {
			element : $('#some-object'),
			element2: $('.some-other-object')
		},

		// options for various functions in this module

		options: {
			some_threshold: 25,
			some_timeout  : 1000
		},

		state: {
        	initialized: false
        },

        // no underscore prefix as I am initialized in functions.js on doc ready

		init: function(){

			this._bind_events();
			this._load_other_function();

			// @ifdef DEBUG
			console.info('Initialized this module.');
			// @endif

		},

		// underscore prefix as I am private

		_bind_events: function(){

			this.$el.element
				.on(s.state.click, '#some-child', this._toggle_something);

			s.el.doc
				.on('steelcase_resize_executed', this._execute_resize);

		},

		_execute_resize: function(){

			alert('This debounced resize event fired after all global resize functions ran.');

		}

		_load_other_function: function(){

			alert('Hello');

		}

	};

##  === code style ===

sorry about the snake_case if youAllCamel.

##  === i18n ===

Any language strings needed in the javascript modules should be put in the tribe_js_i18n function found in functions.php currently.
Please use multi level array and name the parent the name of the module it is used in, with a sub array of strings.
The object is output with localize script and is aliased into the modern_tribe namespace as "nls".
So, to access add_to_save string for the tooltips module below, you would do: nls.tooltips.add_to_save in your tooltips module.
Example of how it looks:


	function tribe_js_i18n(){

		$js_i18n_array = array(
			'help_text' => array(
				'msg_limit'   => __( 'There is a limit to the messages you can post.' )
			),
			'tooltips' => array(
				'add_to_save'   => __( 'Add Photo to Saved Items' ),
				'in_this_photo' => __( 'Products in this photo' )
			)
		);
		return $js_i18n_array;

	}



##  === Markup ===


[1] contains base markup for all templates including base class names.

[1] also utilizes:

* HTML5
* Schema microdata: [http://schema.org]http://schema.org
* WAI-ARIA Landmark Roles: [http://www.w3.org/WAI/intro/aria.php]http://www.w3.org/WAI/intro/aria.php


*ID & Class naming conventions*

* Use dashes to separate words
* Stick to parent item as prefix for child classes, see example below:

<article class="entry-content">
	<h2 class="entry-title">Title</h2>
	<p class="entry-meta">Date</p>
</article>


##  === Functions & PHP ===

@back-end peeps

We have a base functions.php and core functionality mu-plugin for [1].

*NOTE: always defer to the back-end dev(s) on the best way to setup or create new functions and ask if you have questions or need help*

