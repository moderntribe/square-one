# Block Middleware

- [Introduction](#introduction)
  - [Use Cases](#use-cases)
- [Building Custom Block Field/Model Middleware](#building-custom-block-fieldmodel-middleware)
- [Registering Middleware](#registering-middleware)
  - [Register Field Middleware](#register-field-middleware)
  - [Register Model Middleware](#register-model-middleware)
  - [Assign Blocks To Middleware](#assign-blocks-to-middleware)

## Introduction

Block middelware is loosely modeled after [Laravel's Request Middleware](https://laravel.com/docs/middleware).

This feature gives us the power to do two things:

1. Modify a block's `Block_Config` just before it's registered with ACF.
2. Modify a block's `Base_Model` before data is passed to a controller.

This utilizes a Pipeline design pattern, where the block can be passed to unlimited amount of middleware in the order they are registered and each stage and decided if it's going to modify the block in some way or just pass it on to the next stage.

### Use Cases

1. Global or shared field systems. Create a set of ACF fields, inject them into a block, and then you can change that field in a single spot and have it update across all blocks where it's enabled.

## Building Custom Block Field/Model Middleware

TODO: add `wp s1 generate middleware` command to automate this.

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

### Assign Blocks To Middleware

Block models under `MODEL_MIDDLEWARE` constant will be passed through all model middleware, e.g.

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
