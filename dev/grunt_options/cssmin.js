/**
 *
 * Module: grunt-contrib-cssmin
 * Documentation: https://github.com/gruntjs/grunt-contrib-cssmin
 *
 */

module.exports = {

	themecss: {
        options: {
            banner: '/* Square One: Global CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
        },
    	files: {
      		'<%= pkg._themepath %>/css/dist/master.min.css' : ['<%= pkg._themepath %>/css/master.css']
    	}
	},

	themelegacy: {
		options: {
      		banner: '/* Square One: Legacy CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
    	},
    	files: {
      		'<%= pkg._themepath %>/css/dist/legacy.min.css' : ['<%= pkg._themepath %>/css/legacy.css']
    	}
	},

	themeeditor: {
        options: {
            banner: '/* Square One: Visual Editor CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
        },
        files: {
            '<%= pkg._themepath %>/css/admin/dist/editor-style.min.css' : ['<%= pkg._themepath %>/css/admin/editor-style.css']
        }
    },

    themelogin: {
        options: {
            banner: '/* Square One: WordPress Login CSS - <%= grunt.template.today("mm-dd-yyyy") %> */'
        },
    	files: {
        	'<%= pkg._themepath %>/css/admin/dist/login.min.css' : ['<%= pkg._themepath %>/css/admin/login.css']
    	}
    }

};