/**
 *
 * Module: grunt-preprocess
 * Documentation: https://npmjs.org/package/grunt-preprocess
 *
 */

module.exports = {

	options: {
		context: {}
	},

	themescripts: {
		files: {
			'<%= pkg._themepath %>/js/scripts.processed.js' : '<%= pkg._themepath %>/js/scripts.js'
		}
	},
	
	themelibs: {
		files: {
			'<%= pkg._themepath %>/js/libs.processed.js' : '<%= pkg._themepath %>/js/libs.js'
		}
	}

};