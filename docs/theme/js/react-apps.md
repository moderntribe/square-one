# React Apps

## Overview

This system has been engineered to allow for easy injection of react apps as part of the main js bundle as needed. It also allows for hot module replacement mode, vendor bundle management and more with ease. You also get a full example app.

## How To Inject An App

First off, search for @EXAMPLE_REACT_APP to find the entry point of the example app that had been supplied for you, and the area in ready js that shows you how to inject the app as a webpack chunk.

There is one piece of config in the babelrc you have to add as well. Paste "react-hot-loader/babel" as the first entry in the plugins array.

Once you've checked out the code, make a copy of the Example app in the themes/core/js/apps directory and name it. Then head on over to the package.json file and add a new script like yo: `"app:YOUR_APP_NAME": "cross-env NODE_ENV=YOUR_APP_NAME webpack-dev-server --hot --https --port 3000",`

Next up, copy example.js in the webpack dir at root and make it point to your new apps entry point.

Finally, when you are ready to begin react dev on any app, start up your script with `yarn app:YOUR_APP_NAME` and make sure you have `define( 'HMR_DEV', true );` on. This will exclude app chunks from loading in the main bundle so that your app doesnt double load when you are in HMR mode.

## Notes

This framework is opinionated. We are aiming to standardize our react code. To work effectively in this system you need to have a working knowledge of redux and redux sagas. Please think about modularity as well, store anything that could be used by other react apps in the system in the common directory. When making api requests always use the api.js retrieve function. Avoid component state if possible.

## Running the Example App

If you actually want to run the example app, uncomment the code in ready.js you find when you search @EXAMPLE_REACT_APP, add the hotloader code discussed above to babelrc and run a dist/spin up hmr. Then create a page and use the example react app page template.


## Table of Contents

* [Overview](/docs/theme/js/README.md)
* [Code Splitting](/docs/theme/js/code-splitting.md)
* [Polyfills](/docs/theme/js/polyfills.md)
* [Selectors](/docs/theme/js/selectors.md)
* [Events](/docs/theme/js/events.md)
* [Jquery](/docs/theme/js/jquery.md)
* [React Apps](/docs/theme/js/react-apps.md)