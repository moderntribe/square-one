/**
 * Temporary workaround for ssl issues
 * https://github.com/mzabriskie/axios/issues/535#issuecomment-262299969
 */
process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
module.exports = function (grunt) {
	/**
	 *
	 * Function to return object from grunt task options stored as files in the 'grunt_options' folder.
	 *
	 */

	function loadConfig(path) {

		var glob = require('glob');
		var object = {};
		var key;

		glob.sync('*', { cwd: path }).forEach(function (option) {
			key = option.replace(/\.js$/, '');
			object[key] = require(path + option);
		});

		return object;
	}

	/**
	 *
	 * Start up config by reading from package.json.
	 *
	 */

	var dev = grunt.file.exists('local-config.json') ? grunt.file.readJSON('local-config.json') : { proxy: 'square1.tribe', certs_path: '' };

	var config = {
		pkg: grunt.file.readJSON('package.json'),
		dev: dev,
	};

	/**
	 *
	 * Extend config with all the task options in /options based on the name, eg:
	 * watch.js => watch{}
	 *
	 */

	grunt.util._.extend(config, loadConfig('./grunt_options/'));

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
	 * and load grunt tasks that match the provided patterns, eg 'grunt' below.
	 *
	 */

	require('load-grunt-tasks')(grunt);

	/**
	 *
	 * Tasks are registered here. Starts with default, which is run by simply running 'grunt' in your cli.
	 * All other use grunt + taskname.
	 *
	 */

	grunt.registerTask(
		'default', [
			'dist',
		]);

	grunt.registerTask(
		'wp-admin', [
			'postcss:themeWPAdmin',
			'postcss:themeWPAdminMin',
		]);

	grunt.registerTask(
		'wp-editor', [
			'postcss:themeWPEditor',
			'postcss:themeWPEditorMin',
			'header:themeWPEditor',
		]);

	grunt.registerTask(
		'wp-login', [
			'postcss:themeWPLogin',
			'postcss:themeWPLoginMin',
			'header:themeWPLogin',
		]);

	var le = grunt.option('le') || 'mac';

	grunt.registerTask(
		'build', [
			'clean:themeMinCSS',
			'postcss:theme',
			'postcss:themeMin',
			'header:themePrint',
			'header:theme',
			'postcss:themeWPEditor',
			'postcss:themeWPEditorMin',
			'postcss:themeWPAdmin',
			'postcss:themeWPAdminMin',
			'header:themeWPEditor',
			'postcss:themeWPLogin',
			'postcss:themeWPLoginMin',
			'header:themeWPLogin',
			'postcss:themeLegacy',
			'postcss:themeLegacyMin',
			'header:themeLegacy',
			'clean:themeMinJS',
			'copy:themeJS',
			'webpack',
			'uglify:themeMin',
			'concat:themeMinVendors',
			'clean:themeMinVendorJS',
			'lineending:' + le,
			'setPHPConstant',
		]);

	grunt.registerTask(
		'test', [
			// 'accessibility',
			'shell:test',
		]);

	grunt.registerTask(
		'lint', [
			'eslint',
			'postcss:themeLint',
		]);

	grunt.registerTask(
		'legacy', [
			'postcss:themeLegacy',
			'postcss:themeLegacyMin',
			'header:themeLegacy',
		]);

	grunt.registerTask(
		'cheat', [
			'shell:install',
			'concurrent:dist',
			'lineending:' + le,
		]);

	grunt.registerTask(
		'dist', [
			'shell:install',
			'shell:test',
			'concurrent:preflight',
			'concurrent:dist',
			'lineending:' + le,
		]);

	grunt.registerTask(
		'dev', [
			'browserSync:dev',
			'watch',
		]);

	grunt.registerTask(
		'devDocker', [
			'browserSync:devDocker',
			'watch',
		]);

	grunt.registerTask(
		'icons', [
			'clean:coreIconsStart',
			'unzip:coreIcons',
			'copy:coreIconsFonts',
			'copy:coreIconsStyles',
			'copy:coreIconsVariables',
			'replace:coreIconsStyle',
			'replace:coreIconsVariables',
			'header:coreIconsStyle',
			'header:coreIconsVariables',
			'footer:coreIconsVariables',
			'concurrent:dist',
			'clean:coreIconsEnd',
			'lineending:' + le,
		]);

};
