/**
 *
 * Module: grunt-contrib-copy
 * Documentation: https://github.com/gruntjs/grunt-contrib-copy
 * Example:
 *
 */

module.exports = {
	themeJS: {
		files: [
			{
				expand: true,
				flatten: true,
				src: [
					'<%= pkg._npmpath %>/babel-polyfill/dist/polyfill.js',
					'<%= pkg._npmpath %>/jquery/dist/jquery.js',
					'<%= pkg._npmpath %>/jquery/dist/jquery.min.js',
					'<%= pkg._npmpath %>/jquery/dist/jquery.min.map',
					'<%= pkg._componentpath %>/theme/js/globals.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/object-fit/ls.object-fit.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/parent-fit/ls.parent-fit.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/respimg/ls.respimg.js',
					'<%= pkg._npmpath %>/lazysizes/plugins/bgset/ls.bgset.js',
					'<%= pkg._npmpath %>/lazysizes/lazysizes.js',
					'<%= pkg._npmpath %>/tota11y/build/tota11y.min.js',
					'<%= pkg._npmpath %>/webfontloader/webfontloader.js',
				],
				dest: '<%= pkg._corethemepath %>/js/vendor/',
			},
		],
	},

	deploy: {
		files: [
			// WordPress core
			{
				expand: true,
				cwd: 'wp',
				src: [
					'**',
					'!**/wp-content/**',
				],
				dest: '<%= pkg._deploypath %>/',
			},

			// plugins and themes
			{
				expand: true,
				cwd: 'wp-content',
				src: [
					'mu-plugins/**',
					'plugins/**',
					'themes/**',
					'!**/dev/**',
					'!**/tests/**',
					'!**/*.sh',
					'!**/.git',
					'!**/phpunit.xml',
					'!**/codeception.yml',
					'!**/composer.json',
					'!**/composer.lock',
					'!**/memcached.php',
					'!**/object-cache.php',
					'!**/core/vendor/phpunit/**', // causes deploy errors on WP Engine
					'!**/*.flv',
					'!**/*.mp3',
					'!**/*.mp4',
					'!**/*.exe',
					'!**/autoload_static.php',
				],
				dest: '<%= pkg._deploypath %>/wp-content/',
			},

			// config
			{
				nonull: true,
				src: 'build-process.php',
				dest: '<%= pkg._deploypath %>/',
			},
			{
				nonull: true,
				src: 'general-config.php',
				dest: '<%= pkg._deploypath %>/',
			},
			{
				nonull: true,
				src: '.htaccess',
				dest: '<%= pkg._deploypath %>/',
			},
		],
	},
};
