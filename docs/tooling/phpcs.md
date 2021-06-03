# PHPCS

PHP Code Sniffing is the process of evaluating code formatting in an IDE to verify that all developers are using the same syntax and style. At Tribe, this keeps us all on the same page and makes sure that we're all conforming to the same coding standards and best practices.

Whether you're using PhpStorm, VS Code, or another IDE, everyone needs to know how to get PHPCS running correctly to enforce a consistent coding style. Reviewing consistently formatted code helps reduce the cognitive load of peer reviews.

## Visual Studio Code (VS Code)
Download and enable the [PHPCS plugin by Ioannis Kappas](https://marketplace.visualstudio.com/items?itemName=ikappas.phpcs)

SquareOne installs PHPCS on a per-project basis, so there is no need to install PHPCS globally. If you install it globally, know that you'll need to make sure your executable is following the correct phpcs.xml found in your project's root.

If you're using the plugin linked above, you shouldn't need to worry about global vs. local. It is smart enough to look at the `composer.json` to determine if it should use the project's phpcs or your global executable.

One configuration you'll probably want to include in your `settings.json` is
```js
"phpcs.showSources": true
```
This setting will display the exact ruleset being used to highlight an error in your IDE and will help you determine what the issue is.

## PhpStorm

TODO
