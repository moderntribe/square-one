#  Theme Javascript

All theme javascript work is done in the js/src folder. You will need to run `grunt dev` or `grunt watch` during development from the project root. [Webpack](https://webpack.github.io/docs/) is used for bundling and es2015 module syntax is used. Organizing your code is up to you, but generally we create a tree structure that often relates to the php content structure and postcss partials structure. During development you'll also want to ensure you have SCRIPT_DEBUG set to true in your local-config.php.

There is a utils folder with common code we use, dont worry about not using anything from it, if you dont call a file down your entry point chain it wont be included in the browser bundle. 

## Linting

When running the dist task that generates minified bundles a preflight task is run. One of the tasks lints the js folder to Airbnb's styleguide for javascript, the most widely used styleguide in jsland. We have tweaked a couple of settings, but just around tab spacing and a couple of react rules. You can read up on that guide [here](https://github.com/airbnb/javascript). We have added a jscs.json config at root to help you get your ide highlighting to this ruleset, please check the ide.md in guides to learn more.

## Jquery

Jquery is available inside the es2015 modules as $, write what you are comfortable with as long as it performs well and passes linting. We do prefer native javascript when possible.
