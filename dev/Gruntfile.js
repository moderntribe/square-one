module.exports = function(grunt) {

	/**
	 *
	 * Function to return object from grunt task options stored as files in the "grunt_options" folder.
	 *
	 */

	function load_config(path) {

		var glob = require('glob'),
			object = {},
			key;

		glob.sync('*', {cwd: path}).forEach(function(option) {
			key = option.replace(/\.js$/,'');
			object[key] = require(path + option);
		});

		return object;
	}

	/**
	 *
	 * Start up config by reading from package.json.
	 *
	 */

	var dev = grunt.file.exists('local-config.json') ? grunt.file.readJSON('local-config.json') : {"proxy": "square.dev"};

	var config = {
		pkg: grunt.file.readJSON('package.json'),
		dev: dev
	};

	/**
	 *
	 * Extend config with all the task options in /options based on the name, eg:
	 * watch.js => watch{}
	 *
	 */

	grunt.util._.extend(config, load_config('./grunt_options/'));

	/**
	 *
	 *  Apply config to Grunt.
	 *
	 */

	grunt.initConfig(config);

	/**
	 *
	 * Usually you would have to load each task one by one.
	 * The load grunt tasks module installed here will read the dependencies/devDependencies/peerDependencies in your package.json
	 * and load grunt tasks that match the provided patterns, eg "grunt" below.
	 *
	 */

	require('load-grunt-tasks')(grunt);

	/**
	 *
	 * Now we need to set grunt base to parent directory since we wrapped up our tools in the dev folder.
	 *
	 */

	grunt.file.setBase('../');

	/**
	 *
	 * Tasks are registered here. Starts with default, which is run by simply running "grunt" in your cli.
	 * All other use grunt + taskname.
	 *
	 */

	grunt.registerTask(
		'default', [
			'auto_install:main',
			'clean:thememincss',
			'clean:thememinjs',
			'copy:theme',
			'webpack:themedev',
			'uglify:thememin',
			'sass:theme',
			'combine_mq:theme',
			'postcss:theme_prefix',
			'postcss:theme_min',
			'header:themecss',
			'header:printcss',
			'header:themeeditor',
			'header:themelogin',
			'clean:theme',
			'setPHPConstant'
		]);

	grunt.registerTask(
		'dist', [
			'auto_install:main',
			'clean:thememincss',
			'clean:thememinjs',
			'copy:theme',
			'webpack:themedev',
			'uglify:thememin',
			'sass:theme',
			'combine_mq:theme',
			'postcss:theme_prefix',
			'postcss:theme_min',
			'header:themecss',
			'header:printcss',
			'header:themeeditor',
			'header:themelogin',
			'clean:theme',
			'setPHPConstant'
		]);

	grunt.registerTask(
		'legacy', [
			'sass:legacy',
			'postcss:theme_legacy_prefix',
			'postcss:theme_legacy_min',
			'header:legacycss',
			'clean:legacy'
		]);

	grunt.registerTask(
		'dev', [
			'browserSync',
			'watch'
		]);

};
