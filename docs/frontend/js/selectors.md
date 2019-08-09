# Dom Selectors

 We are going to be using a data selector for any nodes that javascript uses to separate our css styling selectors from our js selectors. This system has a tool to assist with this.

The tool getNodes is designed to look for items with the attribute `data-js="selector"` . You should use this wherever possible. It uses querySelectorAll under the hood, and has some helpers and custom standards in place.

`tools.getNodes(selector, convertToArray, parentNode, custom)`

* **selector:** the selector string (if custom false searches for [data-js="selector"])
* **convertToArray:** _(default: false)_ convert nodelist to native js array
* **parentNode:** _(default: document)_ The node to search from.
* **custom:** _(default: false)_ If true won't search for a [data-js="selector"], just the selector string itself

This function is the only function you should use to get js nodes you have control over. It can be as simple as using the selector only (which will search from document down and return a NodeList):

`tools.getNodes('happiness')` 

returns a nodelist for all instances of `[data-js="happiness"]` in the dom. 

`tools.getNodes('happiness')[0]` 

Returns the first node or null, ready for you to act on it:

``` javascript
import * as tools from '../utils/tools';

const el = {
	container: tools.getNodes('program-filters')[0],
};

const init = () => {
	if (!el.container) {
		return;
	}
	
	console.log(el.container.getAttribute('something'))
	
	// stuff for module
};
```

Now lets get a group of child nodes we want to loop over with native javascript forEach looking from a parent node, not document.

``` javascript
import * as tools from '../utils/tools';

const el = {
	container: tools.getNodes('program-filters')[0],
};

const setActiveTriggers = () => {
	el.filterTrigger.forEach((trigger) => {
		// do something with the node trigger
	});
};

const cacheElements = () => {
	el.filterTrigger = tools.getNodes('filter-trigger', true, el.container);
};

const init = () => {
	if (!el.container) {
		return;
	}
	
	cacheElements();
};

```

In this case we searched from `el.container` to find all children with `data-js="filter-trigger"` and passed the true flag to tell my getNodes function to convert the nodelist to a plain array. This allows us to use the native es5 forEach loop on it.

## Table of Contents

* [Overview](/docs/frontend/js/README.md)
* [Code Splitting](/docs/frontend/js/code-splitting.md)
* [Polyfills](/docs/frontend/js/polyfills.md)
* [Selectors](/docs/frontend/js/selectors.md)
* [Events](/docs/frontend/js/events.md)
* [Jquery](/docs/frontend/js/jquery.md)
* [React Apps](/docs/frontend/js/react-apps.md)
