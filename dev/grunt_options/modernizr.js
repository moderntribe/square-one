/**
 *
 * Module: grunt-modernizr
 * Documentation: https://npmjs.org/package/grunt-modernizr
 *
 */

module.exports = {

    theme: {

        "devFile" : "<%= pkg._themepath %>/js/modernizr-dev.js",
        "outputFile" : "<%= pkg._themepath %>/js/modernizr.js",

        "extra" : {
            "shiv" : true,
            "printshiv" : false,
            "load" : true,
            "mq" : true,
            "cssclasses" : true
        },

        "extensibility" : {
            "addtest" : true,
            "prefixed" : true,
            "teststyles" : true,
            "testprops" : true,
            "testallprops" : true,
            "hasevents" : false,
            "prefixes" : true,
            "domprefixes" : true
        },

        "uglify" : true,

        "tests" : [
            'css_boxsizing',
            'css_mediaqueries'
        ],

        "parseFiles" : true,
        "files" : {
            "src": [
                '<%= pkg._themepath %>/scss/**/**/*.scss',
                '<%= pkg._themepath %>/scss/**/*.scss',
                '<%= pkg._themepath %>/scss/*.scss',
                '<%= pkg._themepath %>/js/scripts.js'
            ]
        },
        "matchCommunityTests" : true

    }

};