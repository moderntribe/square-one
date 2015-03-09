/**
 *
 * Module: grunt-contrib-handlebars
 * Documentation: https://www.npmjs.org/package/grunt-contrib-handlebars
 *
 */

module.exports = {

	theme: {
		options: {
			namespace: 'modern_tribe.templates',
			processName: function(filePath) {
				filePath = filePath.replace('content/themes/tribe-square-one/js/templates/', '');
				return filePath.replace('.handlebars', '');
			},
			partialsUseNamespace: true,
			partialRegex: /.*/,
			partialsPathRegex: /content\/themes\/tribe-square-one\/js\/templates\/partials\//,
			wrapped:true

		},
		files: {
			'<%= pkg._themepath %>/js/templates.js' : [ '<%= pkg._themepath %>/js/templates/**/*.handlebars']
		}
	}

};