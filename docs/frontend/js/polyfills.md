# Polyfills

If you find yourself needing to use a javascript polyfill then please use the system that's in place.

They are very easy to add. Find your polyfill on npm and `yarn add` it. Then head on over to `wp-content\themes\core\js\src\theme\core\polyfills.js` and import it there. 

Finally, add you feature detect for your polyfill in `wp-content/themes/core/js/src/theme/core/ready.js:38` in the function `browserSupportsAllFeatures`

The concept here is that instead of using multiple imports for each feature detect and polyfill we run one dynamic import for all browsers that fail any one of those checks. Modern browsers will not download the polyfill chunk, and all non compliant browsers just have to handle one extra request. Init of the main js code will only be executed when the polyfill import promise is resolved.

## Table of Contents

* [Overview](/docs/frontend/js/README.md)
* [Code Splitting](/docs/frontend/js/code-splitting.md)
* [Polyfills](/docs/frontend/js/polyfills.md)
* [Selectors](/docs/frontend/js/selectors.md)
* [Events](/docs/frontend/js/events.md)
* [Jquery](/docs/frontend/js/jquery.md)
