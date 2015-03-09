/**
 * Requires underscore.string
 */
Handlebars.registerHelper('slugify', function(str) {
	return new Handlebars.SafeString(_.str.slugify(str));
});