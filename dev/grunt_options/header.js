/**
 *
 * Module: grunt-header
 * Documentation: https://github.com/sindresorhus/grunt-header
 *
 */

module.exports = {

	themecss: {
		options: {
			text: '/* Square One: Global CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
		},
		files  : {
			'<%= pkg._themepath %>/css/dist/master.min.css' : ['<%= pkg._themepath %>/css/dist/master.min.css']
		}
	},

	printcss: {
		options: {
			text: '/* Square One: Print CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
		},
		files  : {
			'<%= pkg._themepath %>/css/dist/print.min.css' : ['<%= pkg._themepath %>/css/dist/print.min.css']
		}
	},

	themeeditor: {
		options: {
			text: '/* Square One: Visual Editor CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
		},
		files  : {
			'<%= pkg._themepath %>/css/admin/dist/editor-style.min.css' : ['<%= pkg._themepath %>/css/admin/dist/editor-style.min.css']
		}
	},

	themelogin: {
		options: {
			text: '/* Square One: WordPress Login CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
		},
		files  : {
			'<%= pkg._themepath %>/css/admin/dist/login.min.css' : ['<%= pkg._themepath %>/css/admin/dist/login.min.css']
		}
	},

	legacycss: {
		options: {
			text: '/* Square One: Legacy Page CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
		},
		files  : {
			'<%= pkg._themepath %>/css/dist/legacy.min.css' : ['<%= pkg._themepath %>/css/dist/legacy.min.css']
		}
	}

};