# Block Editor

## Configuring Core Blocks

WordPress core (and some other plugins) provide a large collection of blocks to get us started.
We'll often want to configure these block to match the nuances of a particular project.

### Removing Block Types

There are some block types that should be removed from the editor. Some reasons include:

* Blocks that encourage a client to make bad design choices
* Blocks that we have replaced with custom block types

To remove a block type, add it to the `\Tribe\Project\Blocks\Blocks_Definer::BLACKLIST` array
in `\Tribe\Project\Blocks\Blocks_Definer`. The block types in this array will be passed to our
admin JS configuration and disabled in the editor.

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

## The Plugin Not Quite Known as GutenPanels

There are many ways to register new block types. We have standardized on
"[GutenPanels](https://github.com/moderntribe/gutenpanels)", our tool that enables block registration
using configuration in PHP.

## Block Type Registration

New block type that we register will be configured in a class that extends 
`\Tribe\Project\Blocks\Block_Type_Config`. You can find the default collection in the
`\Tribe\Project\Blocks\Types` namespace. Each of these classes will implement the `build()` method
to return the configured block type object.

To register the block type defined by your class, add it to the
`\Tribe\Project\Blocks\Blocks_Definer::TYPES` array in `\Tribe\Project\Blocks\Blocks_Definer`.

Some general guidelines to follow:

* Use the `NAME` class constant to declare the block type name.
* Our block type names use the "tribe/" namespace. E.g., "tribe/accordion".
* All field for the block type should have their names declared in class constants.
* Use sidebar sections for block settings content sections for block content.
* Configure classes and styles on fields, sections, and blocks to help them match the HTML structure
  of the frontend templates.

### Nested Block Types

Many panel designs must be composed of multiple nested blocks. When these nested block types
are only used in support of a larger composite (i.e., they will never be added independently as
top-level blocks), create their configuration classes in the `\Tribe\Project\Blocks\Types\Support`
namespace. These nested block types are registered the same way as top-level blocks.

Once registered, the nested block types are available for use in "container" fields on their parent
block types. Be sure to call the `set_parents()` method on the block type builder to declare the
parent block types supported by the supporting block.

Generally, our nested block types will not render independently of their parents. Instead, we want
their attributes to be merged into a single structure that is passed to the template. We accomplish
this by calling `merge_nested_attributes()` when registering the nest block type on the container.
Field names on supporting blocks should be declared in constants on the parent block type's class
when the nested blocks attributes are merged into the parent.

Note that a block type can only have one container field, but that we can nest containers infinitely
deep to support complex block structures.

## Block Type Templates

All of the blocks that we register will use a WordPress filter to render the frontend template. We
set a template Controller to hook into this filter to render our block types.

A block's template Controller should extend `\Tribe\Project\Templates\Controllers\Block\Block_Controller`.
It is responsible for transforming a block's saved attributes (accessible via `$this->attributes`) into
Components to render the block.

To register the Controller class that will handle a given block type, add it to the
`\Tribe\Project\Blocks\Blocks_Definer::CONTROLLER_MAP` array in `\Tribe\Project\Blocks\Blocks_Definer`.
This array should map the block type's `NAME` to the Controller's `class`. Any block types
not listed here will not be rendered by our Controllers. The
`\Tribe\Project\Templates\Controllers\Block\Debug` Controller provides a default implentation
for initial testing. This Controller will print all of the block's attributes using `print_r()`.
