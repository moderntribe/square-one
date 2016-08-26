#  Theme Javascript

All theme javascript work is done in the js/src folder. You will need to run `grunt dev` or `grunt watch` during development from the project root. [Webpack](https://webpack.github.io/docs/) is used for bundling and es2015 module syntax is used. Organizing your code is up to you, but generally we create a tree structure that often relates to the php content structure and postcss partials structure. During development you'll also want to ensure you have SCRIPT_DEBUG set to true in your local-config.php.

There is a utils folder with common code we use, dont worry about not using anything from it, if you dont call a file down your entry point chain it wont be included in the browser bundle. 

## Dom Selectors and Events/Traversing

We are aiming to move away from jquery for event delegation and for loops of dom nodes. We are also going to be using a data selector for any nodes that javascript uses to separate our css styling selectors from our js selectors. This system has two tools to assist with this.

The first tool getNodes is designed to look for items with the attribute `data-js="selector"` . You should use this wherever possible.

`tools.getNodes(selector, convertToArray, parentNode)`

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

For event delegation we often want to use jquery's `on`, to bind a handler to a parent node and list for events on children. This system has a plain js module that does the same task. It's called delegate, here is how to use it:

``` javascript
import delegate from 'delegate';
import * as tools from '../utils/tools';

const el = {
	container: tools.getNodes('program-filters')[0],
};

const handleFilterClick = (e) => {
	const trigger = e.delegateTarget;
	if (trigger.classList.contains('filter__button--active')) {
		trigger.classList.remove('filter__button--active');
	} else {
		trigger.classList.add('filter__button--active');
	}
};

const cacheElements = () => {
	el.filterTrigger = tools.getNodes('filter-trigger', true, el.container);
};

const bindEvents = () => {
	delegate(el.container, '[data-js="filter-trigger"]', 'click', handleFilterClick);
};

const init = () => {
	if (!el.container) {
		return;
	}
	
	cacheElements();
	bindEvents();
};

```

Note here we must use `e.delegateTarget` not `e.currentTarget`.

## Linting

When running the dist task that generates minified bundles a preflight task is run. One of the tasks lints the js folder to Airbnb's styleguide for javascript, the most widely used styleguide in jsland. We have tweaked a couple of settings, but just around tab spacing and a couple of react rules. You can read up on that guide [here](https://github.com/airbnb/javascript). To get your ide highlighting/hinting to this ruleset, please have your ide parse the .eslintrc at root.

## Jquery

Jquery is available inside the es2015 modules as $, write what you are comfortable with as long as it performs well and passes linting. We do prefer native javascript when possible.
