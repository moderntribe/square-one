# Components

## Overview

Within [1], the majority of default elements are made up of smaller chunks we call "components". For instance, the Post Loop panel is made up of the Title, Card,
and Button components. Each component acts as a re-usable piece of markup that can be included in a wide variety of contexts in order to provide consistent structure,
classing, and styling across the design system. 

In the BE, Component controllers differ from other controllers in a few key ways. First, since Components are abstracted in order to receive data from a variety of sources,
the Controllers are set up to be _injected_ with data from their parent context. For instance, a Button component could get it's URL and Label from the Panel it's in, 
or the Shortcode that includes it, or the Widget that has it as part of its markup. The parent controller handles gathering that data and then sending it to the Component
Controller to render using its Twig template. 

Secondly, Component Controllers have some methods unique to them (or, more accurately, the `Component` class they extend). They are:

### `parse_options()`

In addition to the `get_data()` method that all Controllers must implement, Component controllers must implement a `parse_options()` method. Since Components can
have any number (usually a large number) of arguments, we pass that data to the controller via an `options` array rather than uniquely-named parameters. As a result,
it's important to have a method set up that establishes sensible defaults for the given Component. That's where the `parse_options` method comes into play. In general,
it will take the passed `$options` array and, using `wp_parse_args()`, merge it with an array of defaults in order to ensure that the class doesn't have any missing 
or unset variables.

#### Example

```
protected function parse_options( array $options ): array {
	
	$defaults = [
		static::TEXT        => '',
		static::CLASSES     => [],
		static::PLACEHOLDER => 'assets/img/shim.png',
	];
	
	return wp_parse_args( $options, $defaults );
}
```

### `merge_classes()` and `merge_attrs()`

These two helper functions are used to take arrays of classes or attributes and merge them with user-provided values in order to generate a properly-formatted set of 
values. The return value can either be imploded into a string (if the 3rd parameter is set to `true`), or returned as an array.

#### Examples

```
$this->options[ static::CLASSES ] = [ 'container', 'container--grid' ];

$classes = $this->merge_classes( [ 'c-card', 'c-card--small' ], $this->options[ static::CLASSES ], true );

// result: "c-card c-card--small container container--grid"
```

```
$this->options[ static::ATTRS ] = [ 'data-js' => 'card', 'data-live-text' => true ];

$attrs = $this->merge_attrs( [ 'data-content' => 'Card content here' ], $this->options[ static::ATTRS ], true );

// result: 'data-js="card" data-live-text data-content="Card content here"'
```

## Instantiating a Component

Instantiating a Component is a 2-part process. First, you'll need to set up an `$options` array populated with any values you wish to send to the Controller from
whichever parent Controller you are calling the component. Best practices dictate that we should always use the class constants from the Component Controller when 
setting up the array to avoid mismatches in key names. 

#### Example

```
use Tribe\Project\Templates\Components\Card as CardComponent;

...

$options = [
	CardComponent::TEXT  => 'This is the card text',
	CardComponent::TITLE => $this->get_title(),
];
```

Once the options array is set up, you will pass that array to the `::factory()` method for the given Component Controller. From there, the `::render()` method on the Component Controller will
return properly-formatted HTML as a string. 

#### Example

```
$card_obj = CardComponent::factory( $options );

return $card_obj->render();
```

## Composition with Components - Example Panel

To illustrate a use-case for Components, consider a hypothetical Hero Panel. Let's assume that the Hero Panel has the following inputs:

* **title** - a text input
* **content** - a WYSIWYG field
* **background_image** - an Image field
* **cta** - a Link field (which returns `url`, `label`, and `target` values)


We have components for each of these field types. Rather than making the `twig` template for this Panel with unique markup, it will instead simply echo variables containing
the rendered markup for each of these fields. By doing this, we'll know that that markup and formatting for each component will be consistent with other modules in the
design system which implement the same components. The `hero.twig` template could look something like this:

```
<div class="hero">
	
	{{ background_image }}

	<div class="hero__hd">
		{{ title }}
	</div>
	
	<div class="hero__bd">
		{{ content }}
	</div>
	
	<div class="hero__ft">
		{{ cta }}
	</div>
</div>
```

The `get_data()` method for this Panel controller would look something like this:

```
$data = [
	'background_image' => $this->get_background_image(),
	'title'            => $this->get_title(),
	'content'          => $this->get_content(),
	'cta'              => $this->get_cta(),
];

return $data;
```

Then, for the background image:

```
protected function get_background_image() {

	$options = [
		ImageComponent::IMG_ID       => $this->panel_vars[ HeroPanel::FIELD_BACKGROUND_IMAGE ],
		ImageComponent::AS_BG        => true,
		ImageComponent::USE_LAZYLOAD => false,
	]
	
	$image_obj = ImageComponent::factory( $options );
	
	return $image_obj->render();
}
```

For the title:

```
protected function get_title() {

	$options = [
		TitleComponent::TITLE       => $this->panel_vars[ HeroPanel::FIELD_TITLE ],
		TitleComponent::TAG         => 'h2',
		TitleComponent::CLASSES     => [ 'hero__title' ],
	]
	
	$title_obj = TitleComponent::factory( $options );
	
	return $title_obj->render();
}
```

For the Content:

```
protected function get_content() {

	$options = [
		ContentBlockComponent::TEXT                          => $this->panel_vars[ HeroPanel::FIELD_CONTENT ],
		ContentBlockComponent::CONTENT_BLOCK_CLASSES         => [ 'hero__content' ],
	]
	
	$content_obj = ContentBlockComponent::factory( $options );
	
	return $content_obj->render();
}
```

And finally for the CTA:

```
protected function get_cta() {

	$options = [
		ButtonComponent::URL    => $this->panel_vars[ HeroPanel::FIELD_CTA ]['url'],
		ButtonComponent::LABEL  => $this->panel_vars[ HeroPanel::FIELD_CTA ]['label'],
		ButtonComponent::TARGET => $this->panel_vars[ HeroPanel::FIELD_CTA ]['target'],
	]
	
	$button_obj = ButtonComponent::factory( $options );
	
	return $button_obj->render();
}
```

With this, the Panel is setting up it's data object with values containing the rendered Components, which were generated from the Component Controllers injected with 
the data from the Panel. 

If the context was a Widget instead, the widgets `render()` method would follow the same basic pattern, calling each Component Controller and getting the rendered 
markup from there and displaying it. 

By following this pattern of large blocks made up of small components, consistency across the design system can be achieved much more simply and with more accurate results.
