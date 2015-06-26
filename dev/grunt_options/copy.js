/**
 *
 * Module: grunt-contrib-copy
 * Documentation: https://npmjs.org/package/grunt-contrib-copy
 * Example:
 *
 	main: {
		files: [
		  // includes files within path
		  {expand: true, src: ['path/*'], dest: 'dest/', filter: 'isFile'},

		  // includes files within path and its sub-directories
		  {expand: true, src: ['path/**'], dest: 'dest/'},

		  // makes all src relative to cwd
		  {expand: true, cwd: 'path/', src: ['**'], dest: 'dest/'},

		  // flattens results to a single level
		  {expand: true, flatten: true, src: ['path/**'], dest: 'dest/', filter: 'isFile'}
		]
  	}
 *
 */

module.exports = {

	deploy: {
		files: [
			// WordPress core
			{
				expand: true,
				cwd: 'wp',
				src: '**',
				dest: '<%= pkg._deploypath %>/'
			},

			// plugins and themes
			{
				expand: true,
				cwd: 'content',
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
					'!**/composer.lock'
				],
				dest: '<%= pkg._deploypath %>/wp-content/'
			},

			// config
			{
				src: 'build-process.php',
				dest: '<%= pkg._deploypath %>/'
			}
		]
	}

};