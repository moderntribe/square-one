
module.exports = {

	options: {
		sourceMap: false,
		compact: false
	},
	theme: {
		files: {
			"<%= pkg._themepath %>/js/scripts.js": "<%= pkg._themepath %>/js/scripts-es6.js"
		}
	}

};
