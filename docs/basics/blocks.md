# Block Editor

## Configuring Core Blocks

WordPress core (and some other plugins) provide a large collection of blocks to get us started.
We'll often want to configure these block to match the nuances of a particular project.

### Adding Block Types

There are some block types that should be removed from the editor. Some reasons include:

* Blocks that encourage a client to make bad design choices
* Blocks that we have replaced with custom block types

By default, we are hiding some blocks and using an "Allow List" to add a block type, add it to the
`\Tribe\Project\Blocks\Blocks_Definer::ALLOWLIST` array in `\Tribe\Project\Blocks\Blocks_Definer`. 
The block types in this array will be passed to through the `allowed_block_types` filter.

Note that some care should be taken to avoid errors when WordPress uses nested block types. For
example, removing the `core/button` block will trigger an error in the editor, because the `core/buttons`
block expects it to be available. They must be removed together.

### Configuring Block Options

Some block types provide various settings/options. E.g., font sizes, colors, styles. These can be
modified with some configuration.

* Block styles can be added to the `\Tribe\Project\Blocks\Blocks_Definer::STYLES` array
  in the `Blocks_Definer`.
* Font sizes can be set in the `\Tribe\Project\Theme\Theme_Definer::CONFIG_FONT_SIZES` array
  in the `Theme_Definer`.
* Color palettes can be set in the `\Tribe\Project\Theme\Theme_Definer::CONFIG_COLORS` array
  in the `Theme_Definer`.
* Gradient palettes can be set in the `\Tribe\Project\Theme\Theme_Definer::CONFIG_GRADIENTS` array
  in the `Theme_Definer`.

## ACF Blocks

There are many ways to register new block types. We have standardized on
"[ACF Blocks](https://www.advancedcustomfields.com/resources/blocks/)", our tool that enables block
registration using configuration in PHP.

## Block Type Registration

New block type that we register will be configured in a class that extends 
`\Tribe\Libs\ACF\Block_Config`. You can find the default collection in the
`\Tribe\Project\Blocks\Types` namespace. Each of these classes will implement the `add_block()` and
 `add_fields()` methods and optionally use `add_settings()` to register blocks and it's fields. See the 
 ACF documentation for registering a block. [ACF Block Registration](https://www.advancedcustomfields.com/resources/acf_register_block_type/)

To register the block type defined by your class, add it to the
`\Tribe\Project\Blocks\Blocks_Definer::TYPES` array in `\Tribe\Project\Blocks\Blocks_Definer` as well
ass the `\Tribe\Project\Blocks\Blocks_Definer::ALLOWLIST` keeping in mind ACF prefixes your block name with `acf/`

Some general guidelines to follow:

* Use the `NAME` class constant to declare the block type name.
* ACF block types automatically prepend the block name with the "acf/" namespace. E.g., "acf/accordion".
* All field and settings for the block type should have their names declared in class constants.

## Block Type Model

All block types will use a Data Model to get and set-up data for use in components used to render your block.
Data models are created in the blocks namespace. E.g., `Tribe\Project\Blocks\Types\Accordion` would
have a `Tribe\Project\Blocks\Types\Accordion\Accordion_Model`

The Data Model extends `\Tribe\Project\Blocks\Types\Base_Model` and implements `get_data()`. You can use the
`get()` method to get values from your ACF block and set a sensible default. 

```php
$this->get( Accordion::TITLE, '' );
```
Data Models are used to keep the data and specifically ACF data separate from the component being use 
to render the block. This allows our block components to be used with blocks or anywhere else needed by your theme.

## Block Type Templates

To connect your Data Model to your component we're using a "route" file. This file is located in the core 
theme in the `/block` directory. The route must be namespace the same as your registered block `NAME` constant.
E.g. `accordion.php`

From the route file, you should use `get_template_part()` to pass your block Model data into the component of choice.

```php
$model = new Accordion_Model( $args[ 'block' ] );
get_template_part( 'components/blocks/accordion/accordion', null, $model->get_data() );
```
