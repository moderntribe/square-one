/**
 *
 * Module: grunt-header
 * Documentation: https://github.com/sindresorhus/grunt-header
 *
 */

module.exports = {
	theme: {
		options: {
			text: '/* Core: Global CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/dist/master.min.css': ['<%= pkg._corethemepath %>/css/dist/master.min.css'],
		},
	},

	themePrint: {
		options: {
			text: '/* Core: Print CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/dist/print.min.css': ['<%= pkg._corethemepath %>/css/dist/print.min.css'],
		},
	},

	themeWPEditor: {
		options: {
			text: '/* Core: Visual Editor CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/editor-style.min.css': ['<%= pkg._corethemepath %>/css/admin/dist/editor-style.min.css'],
		},
	},

	themeWPLogin: {
		options: {
			text: '/* Core: WordPress Login CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/admin/dist/login.min.css': ['<%= pkg._corethemepath %>/css/admin/dist/login.min.css'],
		},
	},

	themeLegacy: {
		options: {
			text: '/* Core: Legacy Page CSS */',
		},
		files: {
			'<%= pkg._corethemepath %>/css/dist/legacy.min.css': ['<%= pkg._corethemepath %>/css/dist/legacy.min.css'],
		},
	},
};
