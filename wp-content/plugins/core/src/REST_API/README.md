# REST API Modifications

This module contains any modifications you would like to make to the REST API, such as registering object meta, parsing and displaying Gutenberg blocks, etc.

## How to hook using this module.

All hooks you add in the `hook()` method of the `REST_API_Main` class will be hooked into the `rest_api_init` action. This is where you'll want to add any actions or filters.

## What can I do with this module?

In the `REST_API_Main` class, you can see one example of how and when to use this class: Adding post meta to the REST output. Another way to use this would be to use the block editor's `parse_blocks` function to add the parsed contents of post blocks to the REST output.

## Changelog
v0.1.0: Submitted this module for addition to Square One.
