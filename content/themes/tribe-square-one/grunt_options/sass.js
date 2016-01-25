/**
 *
 * Module: grunt-sass
 * Documentation: https://github.com/sindresorhus/grunt-sass
 * Example:
 *
 */

module.exports = {

	theme: {
		options: {
			outputStyle: 'nested',
			sourceMap  : false
		},
		files  : {
			'css/master-temp.css' : 'scss/master.scss',
			'css/print-temp.css'  : 'scss/print.scss'
		}
	},

	theme_wp_editor: {
		options: {
			outputStyle: 'nested',
			sourceMap  : false
		},
		files  : {
			'css/admin/editor-style-temp.css' : 'scss/admin/editor-style.scss'
		}
	},

	theme_wp_login: {
		options: {
			outputStyle: 'nested',
			sourceMap  : false
		},
		files  : {
			'css/admin/login-temp.css' : 'scss/admin/login.scss'
		}
	},

	theme_legacy: {
		options: {
			outputStyle: 'nested',
			sourceMap  : false
		},
		files  : {
			'css/legacy-temp.css' : 'scss/legacy.scss'
		}
	}

};