/**
 *
 * Module: grunt-preprocess
 * Documentation: https://npmjs.org/package/grunt-preprocess
 * Example:
 *
 options: {
    context : {
      DEBUG: true
    }
  },
 html : {
    src : 'test/test.html',
    dest : 'test/test.processed.html'
  },
 multifile : {
    files : {
      'test/test.processed.html' : 'test/test.html',
      'test/test.processed.js'   : 'test/test.js'
    }
  },
 inline : {
    src : [ 'processed/*.js' ],
	options: {
		inline : true,
			context : {
			DEBUG: false
		}
	}
	},
 js : {
		src : 'test/test.js',
			dest : 'test/test.processed.js'
	}
 *
 */

module.exports = {

	// auto prefixing

	theme_prefix:{
		options: {
			map: false,
			processors: [
				require('autoprefixer')({browsers: ['last 3 versions', 'ie 9']})
			]
		},
		files:{
			'<%= pkg._themepath %>/css/master.css'             : '<%= pkg._themepath %>/css/master-temp.css',
			'<%= pkg._themepath %>/css/print.css'              : '<%= pkg._themepath %>/css/print-temp.css',
			'<%= pkg._themepath %>/css/admin/editor-style.css' : '<%= pkg._themepath %>/css/admin/editor-style-temp.css',
			'<%= pkg._themepath %>/css/admin/login.css'        : '<%= pkg._themepath %>/css/admin/login-temp.css'
		}
	},

	// minification

	theme_min:{
		options: {
			map: false,
			processors: [
				require('cssnano')()
			]
		},
		files:{
			'<%= pkg._themepath %>/css/dist/master.min.css'             : '<%= pkg._themepath %>/css/master.css',
			'<%= pkg._themepath %>/css/dist/print.min.css'              : '<%= pkg._themepath %>/css/print.css',
			'<%= pkg._themepath %>/css/admin/dist/editor-style.min.css' : '<%= pkg._themepath %>/css/admin/editor-style.css',
			'<%= pkg._themepath %>/css/admin/dist/login.min.css'        : '<%= pkg._themepath %>/css/admin/login.css'
		}
	}


};