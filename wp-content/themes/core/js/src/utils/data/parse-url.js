
const parseUrl = (str, component) => {

	// example: parse_url('http://username:password@hostname/path?arg=value#anchor');
	// returns: {scheme: 'http', host: 'hostname', user: 'username', pass: 'password', path: '/path', query: 'arg=value', fragment: 'anchor'}

	var query;
	var key = ['source', 'scheme', 'authority', 'userInfo', 'user', 'pass', 'host', 'port', 'relative', 'path', 'directory', 'file', 'query', 'fragment'];
	var ini = {};
	var mode = (ini['phpjs.parse_url.mode'] && ini['phpjs.parse_url.mode'].local_value) || 'php';
	var parser = {
		php: /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
		loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/, // Added one optional slash to post-scheme to catch file:/// (should restrict this)
	};

	var m = parser[mode].exec(str);
	var uri = {};
	var i = 14;
	while (i--) {
		if (m[i]) {
			uri[key[i]] = m[i];
		}
	}

	if (component) {
		return uri[component.replace('PHP_URL_', '')
			.toLowerCase()];
	}

	if (mode !== 'php') {
		var name = (ini['phpjs.parse_url.queryKey'] &&
			ini['phpjs.parse_url.queryKey'].local_value) || 'queryKey';
		parser = /(?:^|&)([^&=]*)=?([^&]*)/g;
		uri[name] = {};
		query = uri[key[12]] || '';
		query.replace(parser, function ($0, $1, $2) {
			if ($1) {
				uri[name][$1] = $2;
			}
		});
	}

	uri.source = null;
	return uri;
};

export default parseUrl;
