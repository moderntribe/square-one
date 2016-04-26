/**
 *
 * Module: grunt-header
 * Documentation: https://github.com/sindresorhus/grunt-header
 *
 */

module.exports = {
	theme: {
		options: {
			text: '/* Square One: Global CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/dist/master.min.css': ['<%= pkg._corethemepath %>/css/dist/master.min.css'],
		},
	},

	theme_print: {
		options: {
			text: '/* Square One: Print CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/dist/print.min.css': ['<%= pkg._corethemepath %>/css/dist/print.min.css'],
		},
	},

	theme_wp_editor: {
		options: {
			text: '/* Square One: Visual Editor CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/editor-style.min.css': ['<%= pkg._corethemepath %>/css/admin/dist/editor-style.min.css'],
		},
	},

	theme_wp_login: {
		options: {
			text: '/* Square One: WordPress Login CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/login.min.css': ['<%= pkg._corethemepath %>/css/admin/dist/login.min.css'],
		},
	},

	theme_legacy: {
		options: {
			text: '/* Square One: Legacy Page CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/dist/legacy.min.css': ['<%= pkg._corethemepath %>/css/dist/legacy.min.css'],
		},
	},
};
