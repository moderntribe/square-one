/**
 * The {{#exists}} helper checks if a variable is defined.
 */

Handlebars.registerHelper('exists', function(variable, options) {
	if (typeof variable !== 'undefined') {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
});