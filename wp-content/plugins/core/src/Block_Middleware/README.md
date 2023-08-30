# Block Middleware

- [Introduction](#introduction)
- [Video Guides](#video-guides)
  - [Use Cases](#use-cases)
- [Generate a Block with Middleware](#generate-a-block-with-middleware)
- [Creating Custom Block Field/Model Middleware](#creating-custom-block-fieldmodel-middleware)
- [Registering Middleware](#registering-middleware)
  - [Register Field Middleware](#register-field-middleware)
  - [Register Model Middleware](#register-model-middleware)
  - [Manually Assign Blocks To Middleware](#manually-assign-blocks-to-middleware)

## Introduction

Block middelware is loosely modeled after [Laravel's Request Middleware](https://laravel.com/docs/middleware).

This feature gives us the power to do two things:

1. Modify a block's `Block_Config` just before it's registered with ACF.
2. Modify a block's `Base_Model` before data is passed to a controller.

This utilizes a Pipeline design pattern, where the block can be passed through any number of middleware implementations in the order they are registered. Each stage, or "pipe", can decide if it's going to modify the block in some way and either make a modification and pass it to the next stage, or simply skip doing anything and passing it to the next stage. Your middleware
should always pass the modified data to the next stage.

## Video Guides

- [Block Middleware Overview](https://d.pr/K963cu) - A technical overview of the entire Block Middleware system.

### Color Theme Middleware

- [How to add the Color Theme Middleware to an existing block](https://d.pr/aIi5HY)
- [How to add the Color Theme Middleware to a repeater](https://d.pr/cUAx8b) - **IMPORTANT: This is currently outdated, see the [Color Theme README.md for up-to-date examples](../Blocks/Middleware/Color_Theme/README.md).**

### Post Loop Field Middleware

- [Post Loop Field Overview](https://d.pr/Px5xSe)
- [How to configure the Post Loop Field Middleware](https://d.pr/FicVgp) - **IMPORTANT: This is currently outdated as the middleware key you provide in `get_middleware_params()` should now return an array of `$config` instances. See [generating a new block with Post Loop Field Middleware]**(#example---generate-a-new-block-named-my_custom_block-that-has-post-loop-middleware-support) for the most up-to-date example.
- [How to use the Post Loop Field in non-block controllers](https://d.pr/UP7fcL)

### Use Cases

1. Global or shared field systems. Create a set of ACF fields, inject them into a block, and then you can change that field in a single spot and have it update across all blocks where it's enabled.
2. Modify any fields/field data on the fly, for example of a field had to be renamed, but you want to use the old data until the post is saved by a user.

## Generate a Block with Middleware

New options have been added to the `wp s1 generate block` command, run the following help command to see the options:

> TIP: Remove the `so` prefix if you're not using Tribe's local docker environment.

```shell
so wp help s1 generate block
```

#### Example - Generate a new block named `My_Custom_Block` that has Post Loop Middleware support:**

```shell
so wp -- s1 generate block My_Custom_Block --with-post-loop-middleware
```

The above command will create all the files required to create a block with Post Loop Middleware, the only thing it currently
doesn't do is build the cards/output the posts in the view. Once generated, you can begin to customize the block as you see fit.

If you have made other custom middleware and need to generate a block that has the basic building blocks to configure that middleware, you can pass the `--with-middleware` option.

#### Example - Generate a new block named `My_Other_Custom_Block` that contains the skeleton for a middleware implementation:

```shell
so wp -- s1 generate block My_Other_Custom_Block --with-middleware
```

## Creating Custom Block Field/Model Middleware

Use the CLI generator to create example Block Middleware you can then customize, see available options by running the help command:

> TIP: Remove the `so` prefix if you're not using Tribe's local docker environment.

```shell
so wp s1 help generate block:middleware
```

#### Example - Generate block middleware called `Inject_Css`:

```shell
so wp s1 generate block:middleware Inject_Css
```

The above command will perform the following actions:

1. Generate a `Inject_Css_Field_Middleware` class that injects a text field called Custom CSS into blocks that are configured to process middleware in the [Block_Middleware_Definer](Block_Middleware_Definer.php).
2. Generate a `Inject_Css_Model_Middleware` class that will take the entered CSS class, and merge it into the block's existing CSS classes.
3. Automatically registers the Field/Model Middleware in the [Block_Middleware_Definer](Block_Middleware_Definer.php).
4. Generate integration tests for the Field/Model Middleware that should later be customized by the developer as they make modifications to the example implementation.

> TIP: Not all middleware requires both a Field and Model implementation. Simply delete the classes you don't need and remove them from the [Block_Middleware_Definer](Block_Middleware_Definer.php) and delete respective automated test classes.

You can currently create two types of middleware out of the box:

1. Middleware that extends [Abstract_Field_Middleware](Contracts/Abstract_Field_Middleware.php): This will allow you to modify the fields of a block before it's registered with ACF.
2. Middleware that extends [Abstract_Model_Middleware](Contracts/Abstract_Model_Middleware.php): This will allow you to append data to a block model.

## Registering Middleware

All registrations happen in the [Block_Middleware_Definer](Block_Middleware_Definer.php).

### Register Field Middleware

Add your field middleware to the collection in the order you want it to run:

```php
			/**
			 * Add custom Block Middleware field definitions to dynamically
			 * attach fields to existing blocks.
			 *
			 * @var \Tribe\Project\Block_Middleware\Contracts\Field_Middleware[]
			 */
			self::FIELD_MIDDLEWARE_COLLECTION => DI\add( [
				DI\get( Color_Theme_Field_Middleware::class ),
			] ),
```

### Register Model Middleware

Add your model middleware to the collection in the order you want it to run:

```php
			/**
			 * Add custom Block Model middleware to dynamically append new data
			 * to the existing block's model data.
			 *
			 * @var \Tribe\Project\Block_Middleware\Contracts\Model_Middleware[]
			 */
			self::MODEL_MIDDLEWARE_COLLECTION => DI\add( [
				DI\get( Color_Theme_Field_Model_Middleware::class ),
			] ),
```

### Manually Assign Blocks To Middleware

Block models under the `MODEL_MIDDLEWARE` constant will be passed through all model middleware, e.g.

```php
        use Tribe\Project\Blocks\Types\Accordion\Accordion_Model;
        // etc...
    
	/**
	 * Define the block models that accept middleware.
	 *
	 * A block can also define specific middleware
	 * as an array.
	 *
	 * @phpstan-ignore-next-line
	 *
	 * @link https://github.com/phpstan/phpstan/issues/7273 (phpstan bug)
	 *
	 * @var array<class-string, bool|\Tribe\Project\Block_Middleware\Contracts\Middleware[]>
	 */
	public const MODEL_MIDDLEWARE = [
		Accordion_Model::class    => true,
		Card_Grid_Model::class    => true,
		Content_Loop_Model::class => true,
		Hero_Model::class         => true,
	];
```

Alternatively, you can specify an array of model middleware classes if the block should only use those. For example, if you have many different model middleware classes, the `Accordion_Model` will only pass through the `Color_Theme_Field_Model_Middleware` and ignore the rest.

```php
	use Tribe\Project\Blocks\Types\Accordion\Accordion_Model;
	// etc
    
	public const MODEL_MIDDLEWARE = [
		Accordion_Model::class    => [
			Color_Theme_Field_Model_Middleware::class,
		],
		Card_Grid_Model::class    => true,
		Content_Loop_Model::class => true,
		Hero_Model::class         => true,
	];
```

For block field middleware, it's nearly the same process, but you must utilize the `DI\add()` array function and pass the instance of the block's `Block_Config` class.

```php
			use Tribe\Project\Blocks\Types\Accordion\Accordion;
			//etc
            
			/**
			 * Define the blocks that accept middleware.
			 *
			 * A block can also define specific middleware
			 * as an array.
			 *
			 * @var array<class-string, bool|\Tribe\Project\Block_Middleware\Contracts\Middleware[]>
			 */
			self::BLOCK_MIDDLEWARE            => DI\add( [
				Accordion::class    => true,
				Card_Grid::class    => true,
				Content_Loop::class => true,
				Hero::class         => true,
			] ),
```

Or, selectively run field middleware on certain blocks. The `Hero` block will only pass through the `Color_Theme_Field_Middleware` and ignore any others:

```php
			/**
			 * Define the blocks that accept middleware.
			 *
			 * A block can also define specific middleware
			 * as an array.
			 *
			 * @var array<class-string, bool|\Tribe\Project\Block_Middleware\Contracts\Middleware[]>
			 */
			self::BLOCK_MIDDLEWARE            => DI\add( [
				Accordion::class    => true,
				Card_Grid::class    => true,
				Content_Loop::class => true,
				Hero::class         => [
					Color_Theme_Field_Middleware::class,
				],
			] ),
```
