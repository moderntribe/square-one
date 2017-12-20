# Code Splitting

Everybody loves chunks. In webpack we have the ability to split our code and dynamically inject it to reduce main bundle size and only load some context specific js if it is needed for that page.

Lets take a common chunk we can make use of, a panels chunk. In our ready.js file, we would normally import the panels index file at the top and then execute it inside init as `panels()`; Instead, let's have webpack only fetch and inject that pile of code if a condition is met, in the case the presence of the panel-collection wrapper in the page.

At top of the ready module we'll create an el object and grab the panel collection (and other items down the road as needed).

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

Now when the front end loads this code will only be injected by webpack if it finds el.panels to be set. Note we also pass the node to the default export function since we have it. Not necessary, but if you are using that node in the imported js, may as well pass it instead of duping the get. Also note the naming convention with the comment  webpackChunkName that names it. Otherwise webpack will use integer id's which are hard to trace when you start having mountains of chunks.

Remember, you can chunk anywhere you want, not just in ready.js. But when chunking it is possible to overdo it, remember each chunk reps an http request. So creating 20 micro chunks for every small piece of functionality is not a good idea. Take your larger features that only apply to specific areas of the site and chunk em, but dont go and make every components 20 lines of js a chunk.

## Table of Contents

* [Overview](/docs/theme/js/README.md)
* [Code Splitting](/docs/theme/js/code-splitting.md)
* [Polyfills](/docs/theme/js/polyfills.md)
* [Selectors](/docs/theme/js/selectors.md)
* [Events](/docs/theme/js/events.md)
* [Jquery](/docs/theme/js/jquery.md)