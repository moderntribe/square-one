/**
 *
 * Module: grunt-autoprefixer
 * Documentation: https://www.npmjs.org/package/grunt-autoprefixer
 *
 */

module.exports = {

    theme: {
        options: {
            browsers: ['last 3 version', 'ie 8', 'ie 9']
        },
        files:{
            '<%= pkg._themepath %>/css/master.css'             : '<%= pkg._themepath %>/css/master-temp.css',
            '<%= pkg._themepath %>/css/admin/editor-style.css' : '<%= pkg._themepath %>/css/admin/editor-style-temp.css',
            '<%= pkg._themepath %>/css/admin/login.css'        : '<%= pkg._themepath %>/css/admin/login-temp.css'
        }
    }

};