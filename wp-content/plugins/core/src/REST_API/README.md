# REST API Modifications

This module contains any modifications you would like to make to the REST API, such as registering object meta, parsing and displaying Gutenberg blocks, etc.

## How to hook using this module.

Create a class for each coherent unit of functionality. Most classes will hook into the `rest_api_init` action to
register endpoints or fields.

## What can I do with this module?

In the `Example_One_Field` class, you can see one example of how and when to use this class: Adding post meta to the REST output. Another way to use this would be to use the block editor's `parse_blocks` function to add the parsed contents of post blocks to the REST output.
