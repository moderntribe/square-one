/**
 *
 * Module: grunt-split-styles
 * Documentation: https://www.npmjs.org/package/grunt-split-styles
 *
 */

module.exports = {

    legacy: {
     	options: {
        	pattern: /\.lt-ie[7|8|9]/,
        	output: '<%= pkg._themepath %>/css/legacy.css'
      	},
      	files: {
        	'<%= pkg._themepath %>/css/master.css' : '<%= pkg._themepath %>/css/master.css'
      	}
    }

};