/**
 *
 * Module: grunt-preprocess
 * Documentation: https://npmjs.org/package/grunt-preprocess
 *
 */

var autoprefixer_options = {
	map       : false,
	processors: [
		require('autoprefixer')({browsers: ['last 3 versions', 'ie 10']})
	]
};

var cssnano_options = {
	map       : false,
	processors: [
		require('cssnano')({zindex:false})
	]
};

module.exports = {

	// Task: Auto Prefixing

	theme_prefix: {
		options: autoprefixer_options,
		files  : {
			'<%= pkg._basethemepath %>/css/master.css' : '<%= pkg._basethemepath %>/css/master-temp.css',
			'<%= pkg._basethemepath %>/css/print.css'  : '<%= pkg._basethemepath %>/css/print-temp.css'
		}
	},

	theme_wp_editor_prefix: {
		options: autoprefixer_options,
		files  : {
			'<%= pkg._basethemepath %>/css/admin/editor-style.css' : '<%= pkg._basethemepath %>/css/admin/editor-style-temp.css'
		}
	},

	theme_wp_login_prefix: {
		options: autoprefixer_options,
		files  : {
			'<%= pkg._basethemepath %>/css/admin/login.css' : '<%= pkg._basethemepath %>/css/admin/login-temp.css'
		}
	},

	theme_legacy_prefix: {
		options: {
			map       : false,
			processors: [
				require('autoprefixer')({browsers: ['last 20 versions', 'ie 6']})
			]
		},
		files  : {
			'<%= pkg._basethemepath %>/css/legacy.css' : '<%= pkg._basethemepath %>/css/legacy-temp.css'
		}
	},

	// Task: Minification

	theme_min: {
		options: cssnano_options,
		files  : {
			'<%= pkg._basethemepath %>/css/dist/master.min.css' : '<%= pkg._basethemepath %>/css/master.css',
			'<%= pkg._basethemepath %>/css/dist/print.min.css'  : '<%= pkg._basethemepath %>/css/print.css'
		}
	},

	theme_wp_editor_min: {
		options: cssnano_options,
		files  : {
			'<%= pkg._basethemepath %>/css/admin/dist/editor-style.min.css' : '<%= pkg._basethemepath %>/css/admin/editor-style.css'
		}
	},

	theme_wp_login_min: {
		options: cssnano_options,
		files  : {
			'<%= pkg._basethemepath %>/css/admin/dist/login.min.css' : '<%= pkg._basethemepath %>/css/admin/login.css'
		}
	},

	theme_legacy_min: {
		options: cssnano_options,
		files  : {
			'<%= pkg._basethemepath %>/css/dist/legacy.min.css' : '<%= pkg._basethemepath %>/css/legacy.css'
		}
	}

};