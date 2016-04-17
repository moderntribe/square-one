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
			'<%= pkg._basethemepath %>/js/dist/master.min.js' : [
				'<%= pkg._basethemepath %>/js/vendor/polyfill.js',
				'<%= pkg._basethemepath %>/js/vendor/globals.js',
				'<%= pkg._basethemepath %>/js/dist/scripts.js'
			]
		}
	}

};