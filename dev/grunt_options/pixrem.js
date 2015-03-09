/**
 *
 * Module: grunt-pixrem
 * Documentation: https://github.com/robwierzbowski/grunt-pixrem/
 *
 */

module.exports = {

	theme: {
		options: {
			rootvalue: '16px'
		},
		files:{
			'<%= pkg._themepath %>/css/master-temp.css'             : '<%= pkg._themepath %>/css/master-temp.css',
			'<%= pkg._themepath %>/css/admin/editor-style-temp.css' : '<%= pkg._themepath %>/css/admin/editor-style-temp.css',
			'<%= pkg._themepath %>/css/admin/login-temp.css'        : '<%= pkg._themepath %>/css/admin/login-temp.css'
		}
	}

};