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

	var dev = grunt.file.exists('local-config.json') ? grunt.file.readJSON('local-config.json') : {"proxy": "tribe.dev"};

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
	 * Tasks are registered here. Starts with default, which is run by simply running "grunt" in your cli.
	 * All other use grunt + taskname.
	 *
	 */

	grunt.registerTask(
		'default', [
			'dist'
		]);

	grunt.registerTask(
		'wp-editor', [
			'sass:theme_wp_editor',
			'combine_mq:theme_wp_editor',
			'postcss:theme_wp_editor_prefix',
			'postcss:theme_wp_editor_min',
			'header:theme_wp_editor',
			'clean:theme_wp_editor'
		]);

	grunt.registerTask(
		'wp-login', [
			'sass:theme_wp_login',
			'combine_mq:theme_wp_login',
			'postcss:theme_wp_login_prefix',
			'postcss:theme_wp_login_min',
			'header:theme_wp_login',
			'clean:theme_wp_login'
		]);

	grunt.registerTask(
		'legacy', [
			'sass:theme_legacy',
			'postcss:theme_legacy_prefix',
			'postcss:theme_legacy_min',
			'header:theme_legacy',
			'clean:theme_legacy'
		]);

	grunt.registerTask(
		'dist', [
			'auto_install:main',
			'clean:theme_min_css',
			'clean:theme_min_js',
			'copy:theme',
			'webpack:prod',
			'uglify:theme_min',
			'sass:theme',
			'sass:theme_wp_editor',
			'sass:theme_wp_login',
			'sass:theme_legacy',
			'combine_mq:theme',
			'combine_mq:theme_wp_editor',
			'combine_mq:theme_wp_login',
			'postcss:theme_prefix',
			'postcss:theme_wp_editor_prefix',
			'postcss:theme_wp_login_prefix',
			'postcss:theme_legacy_prefix',
			'postcss:theme_min',
			'postcss:theme_wp_editor_min',
			'postcss:theme_wp_login_min',
			'postcss:theme_legacy_min',
			'header:theme',
			'header:theme_print',
			'header:theme_wp_editor',
			'header:theme_wp_login',
			'header:theme_legacy',
			'clean:theme',
			'clean:theme_wp_editor',
			'clean:theme_wp_login',
			'clean:theme_legacy',
			'setPHPConstant'
		]);

	grunt.registerTask(
		'dev', [
			'auto_install:main',
			'browserSync',
			'watch'
		]);

};
