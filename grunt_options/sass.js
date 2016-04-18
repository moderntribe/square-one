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
			'<%= pkg._corethemepath %>/css/master-temp.css' : '<%= pkg._corethemepath %>/scss/master.scss',
			'<%= pkg._corethemepath %>/css/print-temp.css'  : '<%= pkg._corethemepath %>/scss/print.scss'
		}
	},

	theme_wp_editor: {
		options: default_options,
		files  : {
			'<%= pkg._corethemepath %>/css/admin/editor-style-temp.css' : '<%= pkg._corethemepath %>/scss/admin/editor-style.scss'
		}
	},

	theme_wp_login: {
		options: default_options,
		files  : {
			'<%= pkg._corethemepath %>/css/admin/login-temp.css' : '<%= pkg._corethemepath %>/scss/admin/login.scss'
		}
	},

	theme_legacy: {
		options: default_options,
		files  : {
			'<%= pkg._corethemepath %>/css/legacy-temp.css' : '<%= pkg._corethemepath %>/scss/legacy.scss'
		}
	}

};