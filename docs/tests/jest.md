# Jest

This system uses [Jest](https://facebook.github.io/jest/docs/getting-started.html) for all the javascript testing in place in the theme and various plugins. 

You will find the theme Javascript test directory [here](/wp-content/themes/core/js/test)

When adding new modules please add tests for them as well. Utilities and other code are currently in the process of getting full coverage. Everything has been fully configured and Jest's easy to use syntax should make adding the new spec files drop in and go. 

Refer to the api docs [here](https://facebook.github.io/jest/docs/api.html) as needed

The tests will be executed at the start of every `gulp dist` and on the server when doing pr's

## Table of Contents

* [Overview](/docs/tests/README.md)
* [Codeception](/dev/tests/README.md)
* [Jest](/docs/tests/jest.md)
