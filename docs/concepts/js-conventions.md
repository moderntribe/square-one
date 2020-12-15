# JavaScript Conventions

All theme javascript work is done in the js/src folder. You will need to run `gulp dev` or `gulp watch`
during development from the project root. [Webpack](https://webpack.js.org/) is used for bundling and ES6
module syntax is used. Organizing your code is up to you, but generally we create a tree structure that
often relates to the php content structure and postcss partials structure. During development you'll also
want to ensure you have SCRIPT_DEBUG set to true in your local-config.php.

There is a utils folder with common code we use, dont worry about not using anything from it, if you don't
call a file down your entry point chain it wont be included in the browser bundle. 

## Linting

When running the dist task that generates minified bundles a preflight task is run. One of the tasks
lints the js folder to Airbnb's styleguide for javascript, the most widely used styleguide in jsland.
We have tweaked a couple of settings, but just around tab spacing and a couple of react rules. You can
read up on that guide [here](https://github.com/airbnb/javascript). To get your IDE highlighting/hinting
to this ruleset, please have your ide parse the `.eslintrc` at root.

## Code Splitting

Everybody loves chunks. In webpack we have the ability to split our code and dynamically inject
it to reduce main bundle size and only load some context specific js if it is needed for that page.

Lets take a common chunk we can make use of, a panels chunk. In our ready.js file, we would normally
import the panels index file at the top and then execute it inside init as `panels()`; Instead, let's
have webpack only fetch and inject that pile of code if a condition is met, in the case the presence of
the panel-collection wrapper in the page.

At top of the ready module we'll create an el object and grab the panel collection (and other items
down the road as needed).

```javascript
const el = {
	panels: tools.getNodes('panel-collection')[0],
}
```

Then in the init were we are kicking of modules we add:

```javascript
if (el.panels) {
	import('../panels/index' /* webpackChunkName:"panels" */).then(panels => panels.default(el.panels));
}
```

Now when the front end loads this code will only be injected by webpack if it finds el.panels to be set.
Note we also pass the node to the default export function since we have it. Not necessary, but if you are
using that node in the imported js, may as well pass it instead of duping the get. Also note the naming
convention with the comment  webpackChunkName that names it. Otherwise webpack will use integer id's which
are hard to trace when you start having mountains of chunks.

Remember, you can chunk anywhere you want, not just in ready.js. But when chunking it is possible to
overdo it, remember each chunk reps an http request. So creating 20 micro chunks for every small piece
of functionality is not a good idea. Take your larger features that only apply to specific areas of the
site and chunk em, but dont go and make every components 20 lines of js a chunk.

## Polyfills

If you find yourself needing to use a javascript polyfill then please use the system that's in place.

They are very easy to add. Find your polyfill on npm and `yarn add` it. Then head on over to
`wp-content\themes\core\js\src\theme\core\polyfills.js` and import it there. 

Finally, add you feature detect for your polyfill in `wp-content/themes/core/js/src/theme/core/ready.js:38`
in the function `browserSupportsAllFeatures`

The concept here is that instead of using multiple imports for each feature detect and polyfill we run one
dynamic import for all browsers that fail any one of those checks. Modern browsers will not download the
polyfill chunk, and all non compliant browsers just have to handle one extra request. Init of the main js
code will only be executed when the polyfill import promise is resolved.

## Dom Selectors

We are going to be using a data selector for any nodes that javascript uses to separate our css
styling selectors from our js selectors. This system has a tool to assist with this.

The tool getNodes is designed to look for items with the attribute `data-js="selector"`. You
should use this wherever possible. It uses querySelectorAll under the hood, and has some helpers
and custom standards in place.

`tools.getNodes(selector, convertToArray, parentNode, custom)`

* **selector:** the selector string (if custom false searches for [data-js="selector"])
* **convertToArray:** _(default: false)_ convert nodelist to native js array
* **parentNode:** _(default: document)_ The node to search from.
* **custom:** _(default: false)_ If true won't search for a [data-js="selector"], just the selector string itself

This function is the only function you should use to get js nodes you have control over. It can be
as simple as using the selector only (which will search from document down and return a NodeList):

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

Now lets get a group of child nodes we want to loop over with native javascript forEach
looking from a parent node, not document.

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

In this case we searched from `el.container` to find all children with `data-js="filter-trigger"`
and passed the true flag to tell my getNodes function to convert the nodelist to a plain array.
This allows us to use the native es5 forEach loop on it.

## Events and Delegation

For event delegation we often want to use jQuery's `on`, to bind a handler to a parent node and
list for events on children. This system has a plain js module that does the same task. It's called
`delegate`, here is how to use it:

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

## Jquery

Jquery is available inside the es2015 modules as $, write what you are comfortable with as
long as it performs well and passes linting. It is the WordPress version.

We do prefer native javascript when possible.

