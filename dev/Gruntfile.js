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

	var config = {
		pkg: grunt.file.readJSON('package.json')
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
			'clean:thememincss',
			'clean:thememinjs',
			'concat:libs',
			'concat:scripts',
			'babel:theme',
			'preprocess:themelibs',
			'preprocess:themescripts',
			'handlebars:theme',
			'concat:handlebars',
			'uglify:thememin',
			'modernizr:theme',
			'sass:theme',
			'pixrem:theme',
			'combine_mq:theme',
			'autoprefixer:theme',
			'split_styles:legacy',
			'cssmin',
			'clean:theme',
			'setPHPConstant'
		]);

	grunt.registerTask(
		'dist', [
			'clean:thememincss',
			'clean:thememinjs',
			'concat:libs',
			'concat:scripts',
			'babel:theme',
			'preprocess:themelibs',
			'preprocess:themescripts',
			'handlebars:theme',
			'concat:handlebars',
			'uglify:thememin',
			'modernizr:theme',
			'sass:theme',
			'pixrem:theme',
			'combine_mq:theme',
			'autoprefixer:theme',
			'split_styles:legacy',
			'cssmin',
			'clean:theme',
			'setPHPConstant'
		]);

	var target = grunt.option('target') || 'staging';
	grunt.registerTask(
		'deploy', [
			'setPHPConstant',
			'gitcheckout:' + target,
			'gitpull:' + target,
			'gittag:' + target,
			'gitpush:tags',
			'gitpush:' + target,
			'clean:deploy',
			'copy:deploy',
			'gitadd:deploy',
			'gitcommit:deploy',
			'gitpush:server-' + target,
			'slack_notifier:' + target
		]);
};
