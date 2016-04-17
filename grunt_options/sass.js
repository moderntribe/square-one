/**
 *
 * Module: grunt-sass
 * Documentation: https://github.com/sindresorhus/grunt-sass
 * Example:
 *
 */

var default_options = {
	outputStyle: 'nested',
	sourceMap  : false
};

module.exports = {

	theme: {
		options: default_options,
		files  : {
			'<%= pkg._basethemepath %>/css/master-temp.css' : '<%= pkg._basethemepath %>/scss/master.scss',
			'<%= pkg._basethemepath %>/css/print-temp.css'  : '<%= pkg._basethemepath %>/scss/print.scss'
		}
	},

	theme_wp_editor: {
		options: default_options,
		files  : {
			'<%= pkg._basethemepath %>/css/admin/editor-style-temp.css' : '<%= pkg._basethemepath %>/scss/admin/editor-style.scss'
		}
	},

	theme_wp_login: {
		options: default_options,
		files  : {
			'<%= pkg._basethemepath %>/css/admin/login-temp.css' : '<%= pkg._basethemepath %>/scss/admin/login.scss'
		}
	},

	theme_legacy: {
		options: default_options,
		files  : {
			'<%= pkg._basethemepath %>/css/legacy-temp.css' : '<%= pkg._basethemepath %>/scss/legacy.scss'
		}
	}

};