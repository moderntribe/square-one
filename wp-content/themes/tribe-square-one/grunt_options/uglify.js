/**
 *
 * Module: grunt-contrib-uglify
 * Documentation: https://npmjs.org/package/grunt-contrib-uglify
 *
 */

module.exports = {

	theme_min: {
		options: {
			banner   : '/* Square One: JS Master */\n',
			sourceMap: false,
			compress : {
				drop_console: true
			}
		},
		files  : {
			'js/dist/master.min.js' : [
				'js/vendor/polyfill.js',
				'js/vendor/globals.js',
				'js/dist/scripts.js'
			]
		}
	}

};