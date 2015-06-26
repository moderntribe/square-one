/**
 *
 * Module: grunt-git
 * Documentation: https://www.npmjs.org/package/grunt-git
 *
 *
 */

module.exports = {

	options: {
		token: '<%= pkg._slack_api_token %>',
		channel: '<%= pkg._slack_notification_channel %>',
		username: 'Deployment Bot',
		verbose:true
	},

	staging: {
		options: {
			text: 'Deployment to staging complete at <%= grunt.template.today("isoDateTime") %>'
		}
	},

	production: {
		options: {
			text: 'Deployment to production complete at <%= grunt.template.today("isoDateTime") %>'
		}
	},
};