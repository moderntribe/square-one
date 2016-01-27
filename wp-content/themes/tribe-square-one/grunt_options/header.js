/**
 *
 * Module: grunt-header
 * Documentation: https://github.com/sindresorhus/grunt-header
 *
 */

module.exports = {

	theme: {
		options: {
			text: '/* Square One: Global CSS */'
		},
		files  : {
			'css/dist/master.min.css' : ['css/dist/master.min.css']
		}
	},

	theme_print: {
		options: {
			text: '/* Square One: Print CSS */'
		},
		files  : {
			'css/dist/print.min.css' : ['css/dist/print.min.css']
		}
	},

	theme_wp_editor: {
		options: {
			text: '/* Square One: Visual Editor CSS */'
		},
		files  : {
			'css/admin/dist/editor-style.min.css' : ['css/admin/dist/editor-style.min.css']
		}
	},

	theme_wp_login: {
		options: {
			text: '/* Square One: WordPress Login CSS */'
		},
		files  : {
			'css/admin/dist/login.min.css' : ['css/admin/dist/login.min.css']
		}
	},

	theme_legacy: {
		options: {
			text: '/* Square One: Legacy Page CSS */'
		},
		files  : {
			'css/dist/legacy.min.css' : ['css/dist/legacy.min.css']
		}
	}

};