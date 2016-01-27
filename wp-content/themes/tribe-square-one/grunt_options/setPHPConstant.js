/**
 *
 * Module: gruntphpsetconstant
 * Documentation: https://www.npmjs.org/package/grunt-php-set-constant
 *
 */

module.exports = {

	config: {
		constant: 'BUILD_THEME_ASSETS_TIMESTAMP',
		value   : '<%= grunt.template.today("h.MM.mm.dd.yyyy") %>',
		file    : '<%= pkg._rootpath %>/build-process.php'
	}

};