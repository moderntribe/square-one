# Color Theme Middleware

This block middleware adds a centrally configured ACF swatch field with a single source of color choices to the blocks of your choice. There are two types of color theme middleware described below.

## Color Theme Field Middleware

[This middleware](Fields/Color_Theme_Field_Middleware.php) injects an accordion field with an ACF swatch for the entire block. Paired with the [Color_Theme_Field_Model_Middleware](Models/Color_Theme_Field_Model_Middleware.php), this will automatically merge a configurable color theme CSS class into the model's array of CSS classes (The default is `t-theme-$color-name`) which should automatically be passed to the block's controller `$classes` property.

## Color Theme Repeater Field Middleware

[This middleware](Fields/Color_Theme_Repeater_Field_Middleware.php) is much more advanced and allows you to configure a block to inject the ACF swatch field into a parent ACF field, such as a repeater, group or section.

It will search the existing fields for that parent, and if found, it will insert the color theme field at then of that repeater.

To configure this, your `Block Config` should implement the [Has Middleware Params](../../../Block_Middleware/Contracts/Has_Middleware_Params.php) and assign the unique key to the parent field we need to search for in the `get_middleware_params()` method.

> Important: Certain field types have different prefixes, e.g field_, group_ or s-section, and you must provide the **full** field key in order to find it.

Buttons block example:

```php
<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types\Buttons;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;
use Tribe\Project\Block_Middleware\Contracts\Has_Middleware_Params;
use Tribe\Project\Blocks\Fields\Cta_Field;
use Tribe\Project\Blocks\Fields\Traits\With_Cta_Field;
use Tribe\Project\Blocks\Middleware\Color_Theme\Fields\Color_Theme_Repeater_Field_Middleware;

class Buttons extends Block_Config implements Cta_Field, Has_Middleware_Params {

        // ...regular block code here

	/**
	 * Utilize block middleware to inject a color theme field into the "buttons" repeater.
	 *
	 * @return string[][]
	 */
	public function get_middleware_params(): array {
		return [
			[
				Color_Theme_Repeater_Field_Middleware::MIDDLEWARE_KEY => 'field_' . self::NAME . '_' . self::BUTTONS,
			],
		];
	}
	
}
```

The ACF repeater data will now contain a `color_theme` index with the selected hex code, and now you can adjust any Field Models to accept this property and utilize it as required in your block controllers.

```php
array (
  0 => 
  array (
    'cta' => 
    array (
      'link' => 
      array (
        'title' => 'Hello world!',
        'url' => 'https://square1.tribe/hello-world/',
        'target' => '',
      ),
      'add_aria_label' => false,
      'aria_label' => '',
    ),
    'button_style' => 'primary',
    'color_theme' => '#F0F0F0',
  ),
  1 => 
  array (
    'cta' => 
    array (
      'link' => 
      array (
        'title' => 'Sample Page',
        'url' => 'https://square1.tribe/sample-page/',
        'target' => '',
      ),
      'add_aria_label' => false,
      'aria_label' => '',
    ),
    'button_style' => 'primary',
    'color_theme' => '#FFF',
  ),
)
```
