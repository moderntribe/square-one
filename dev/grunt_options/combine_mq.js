/**
 *
 * Module: grunt-combine-mq
 * Documentation: https://github.com/frontendfriends/grunt-combine-mq
 *
 */

module.exports = {

    theme: {
        files:{
            '<%= pkg._themepath %>/css/master-temp.css'      : '<%= pkg._themepath %>/css/master-temp.css',
            '<%= pkg._themepath %>/css/admin/login-temp.css' : '<%= pkg._themepath %>/css/admin/login-temp.css'
        }
    }

};