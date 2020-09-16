# A Guide to Components

So you’ve started using SquareOne (or, maybe, have been using it for years but like me have found yourself a bit lost
when it comes to the scope of features it offers) and are seeing references to “Components” sprinkled throughout the
codebase. They seem…powerful I guess? At the very least they seem _complicated_ which sometimes is a good indication
that something is powerful even if it’s confusing. 

So in the spirit of clarifying what can be a mystifying system, I want to walk through how Components work,
and how we can use them in our code to provide better, more consistent templating for the Front End of a site. 

## So…what is this component of which you speak?
I like to think of Components as the “building blocks” of our sites. They’re the “parts” that make up a whole page,
and can be as simple as a single button `<button>Click Me!</button` or as complex as an entire Card element with titles,
images, text, and buttons. 

I find it helpful to think of them like one of the fancier LEGO sets. When you build the Millennium Falcon,
you don’t build a different exhaust port from scratch using custom pieces every time; instead, you use the same
smaller parts to build each exhaust port so they are uniform, and then you use those exhaust ports as part of the
Falcon as a whole so that it in turn is uniform. Using the same basic building blocks from the smallest part to the
largest component makes the entire building process more manageable and consistent. 

Components work in a similar fashion. Each page can be broken down into discrete parts, each of which is in turn
made up of _other_ discrete parts. A Slider is really just made up of individual slides, which in turn are really
just composed of text, images, and buttons. By breaking these things down into their own parts and re-using the
underlying markup, we achieve consistent output and much more streamlined designs. 

## Oh. So cookie cutter sites then?
I mean, we definitely _could_ make cookie-cutter sites with Components, but that’s definitely not the goal. Rather
than looking at Components as a set of tools for creating generic solutions for our clients, think of them as a set
of tools that are generalized enough to be configured into _custom_ solutions for a client.

To use the LEGO analogy from before, even the most basic 70’s LEGO set of small colorful bricks can be turned into a
limitless number of creations! The advantage of LEGO isn’t that the end result always looks the same, it’s that they’ve
been engineered to fit together in a consistent matter so the builder can spend less time worrying about how to stick
them together and more time just creating!

That’s our hope with Components. Take away the menial task of making a fresh button from scratch every time and just
focus on providing an existing Button component with the data it needs to render.

## Ok, you’ve got me hooked! So how does a Component work?

Now that I’ve enticed you with talk of LEGO and creations and pie-in-the-sky aspirations of boundless imagination,
let’s get down to the brass tacks of components.

We have four separate, related pieces to consider for a component. Fortunately, many of us have enough fingers to count
that high, so we should be able to make it through. Here they all are in a list we can refer back to later:

1. Template
1. Controller
1. CSS
1. JavaScript

Let's dive in, one at a time, walking through a contrived example to showcase the
salient details. Here's the HTML we want to generate from a Button component:

```html
<button type="button" class="close-button" aria-label="Close">
	<span class="icon-close"></span>Close
</button>
```

## HTML Templates

Our Button component's directory will contain a PHP file named `button.twig`. It will look like:

```php
<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Example\Templates\Components\button\Button_Controller\Button_Controller::factory( $args );
?>
<button <?php echo $c->get_classes(); ?> <?php echo $c->get_attrs(); ?>>
	<?php echo $c->get_content(); ?>
</button>
```

The first few lines contain some boilerplate that we'll see at the top of very template:

1. The `$args` array will contain arguments that have been passed into the template. Note that phpcs doesn't
   know about it, so include a comment to tell it that we know what we're doing.
2. `$c = Button_Controller::factory( $args );` instantiates the relevant controller for the template.

After that, the template is echoing HTML and strings that it gets from the controller.
Components are all about injecting the data where it needs to go. A good Component simply takes what it’s given
and gives it structure. In fact, that’s one of the driving principles behind Components - it separates Logic from
Layout. This system avoids the mess that can result from a PHP file mixing logic in with display. We’ve
all seen this before:

```php
?>
<button
  <?php if ( $classes ) { echo 'class="' . implode( ' ', array_map( 'sanitize_html_class', $classes ) ) . '"' }; ?>
  <?php if ( $aria_label = get_post_meta( $post_id, 'aria-label', true ) ) {
    printf( 'aria-label="%s"', esc_attr( $aria_label ) )
  }; ?>
  <?php // repeat for 20 other attributes we might have ?>
>
  <?php if ( $icon ) { ?>
    <span class="icon-<?php echo sanitize_html_class( $icon ); ?>"></span>
  <?php } ?>
  <?php echo $text; ?>
</button>
```

## Controllers

Our template echoes data that it gets from the Controller. Any logic surrounding that data should be delegated to
the Controller. Here's what a controller for our button might look like:

```php
<?php
declare( strict_types=1 );

namespace Tribe\Example\Templates\Components\button;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Deferred_Component;

class Button_Controller extends \Tribe\Project\Templates\Components\Abstract_Controller {
	public const TYPE       = 'type';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const CONTENT    = 'content';
	public const ARIA_LABEL = 'aria_label';

	private string $content;
	private string $type;
	private array  $classes;
	private array  $attrs;
	private string $aria_label;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes    = (array) $args[ self::CLASSES ];
		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->type       = (string) $args[ self::TYPE ];
		$this->aria_label = (string) $args[ self::ARIA_LABEL ];
		$this->content    = $args[ self::CONTENT ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES    => [],
			self::ATTRS      => [],
			self::TYPE       => '',
			self::ARIA_LABEL => '',
			self::CONTENT    => '',
		];
	}

	protected function required(): array {
		return [
          self::CLASSES => [ 'this-is-a-button' ],
        ];
	}

	public function has_content(): bool {
		return ! empty( $this->content );
	}

	public function get_content(): string {
		return $this->content;
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		$attributes = $this->attrs;

		if ( $this->type ) {
			$attributes['type'] = $this->type;
		}

		if ( $this->aria_label ) {
			$attributes['aria-label'] = $this->aria_label;
		}

		return Markup_Utils::concat_attrs( $attributes );
	}
}

```

There's a lot here, so let's unpack one piece at a time.

```php
class Button_Controller extends \Tribe\Project\Templates\Components\Abstract_Controller {}
```

Our `Button_Controller` extends `Abstract_Controller`. This abstract base class provides some of the structure and
shared functionality that all of our controllers depend on.

```php
public const TYPE       = 'type';
public const CLASSES    = 'classes';
public const ATTRS      = 'attrs';
public const CONTENT    = 'content';
public const ARIA_LABEL = 'aria_label';
```

A bunch of constants! You’ll recognize this from all the other SquareOne code you’ve looked at. We love constants.
And who wouldn’t? They let us define strings right within a Class and reference those strings in other classes when
needed. Goodbye hardcoding! Goodbye search-and-replace-all-instances-whoops-I-forgot-one-now-the-site-is-white
-screening-time-to-update-my-resume! Constants are great.

We set up constants for the names of all arguments that might be passed to our temlates.. This is handy for a couple
reasons - first, it allows us to use an IDE’s autocomplete when instantiating requesting this component (more on that
later). Second, it acts as a sort of reference for what this component accepts as options.
 
```php
private string $content;
private string $type;
private array  $classes;
private array  $attrs;
private string $aria_label;
```

Following the constants are the properties of our controller class. These will often (but not quite always) correspond
to the arguments the component accepts. Some properties, like `$classes` and `$attrs`, will be common to almost all
components. Others depend on the needs of this particular component.

Wherever possible, properties should be private and typed. If it may have multiple types, give it a docblock to
specify all of the valid types.

```php
public function __construct( array $args = [] ) {
    $args = $this->parse_args( $args );

    $this->classes    = (array) $args[ self::CLASSES ];
    $this->attrs      = (array) $args[ self::ATTRS ];
    $this->type       = (string) $args[ self::TYPE ];
    $this->aria_label = (string) $args[ self::ARIA_LABEL ];
    $this->content    = $args[ self::CONTENT ];
}
```

The constructor for our controller is responsible for mapping the arguments we receive to those properties.
`parse_args()` is a helper function we inherit from the parent class to fill in the args with defaults. Which brings
us to:

```php
protected function defaults(): array {
    return [
        self::CLASSES    => [],
        self::ATTRS      => [],
        self::TYPE       => '',
        self::ARIA_LABEL => '',
        self::CONTENT    => '',
    ];
}
```

Use the `defaults()` method to configure those default values. Often we'll just want propertly typed empty values,
but sometimes we'll have other reasonable defaults. 

```php
protected function required(): array {
    return [
      self::CLASSES => [ 'this-is-a-button' ],
    ];
}
```

Anything returned in the `defaults()` array may be overridden by arguments passed into the component. To ensure that
certain values always exists, add them in the `required()` array. These will be merged with the passed arguments.
Note that this only works with arguments that are arrays to begin with. If you're setting a required value for a scalar
argument, it shouldn't be an argument in the first place.

The rest of the class consists of various `get_*` and `has_*` public methods. These comprise the public API of the
controller that will be used by the template.

Some of this will just returning the value of an existing property.

```php
public function get_content(): string {
    return $this->content;
}
```

Others may massage that data a little bit, for example sanitizing and merging classes into a class string.

```php
public function get_classes(): string {
    return Markup_Utils::class_attribute( $this->classes );
}
```

Others may combine multiple properties to produce a single value, as we do here with the attributes.

```php
public function get_attrs(): string {
    $attributes = $this->attrs;

    if ( $this->type ) {
        $attributes['type'] = $this->type;
    }

    if ( $this->aria_label ) {
        $attributes['aria-label'] = $this->aria_label;
    }

    return Markup_Utils::concat_attrs( $attributes );
}
```

## CSS

We could stop here and have a fully functional Component. But wait, there's more! Component styles live right next to
the Template and the Controller inside the Component's directory. It starts with `index.pcss`. This is a clearing
-house to load additional files found in the Component's `css` subdirectory. The build system will find this
`index.pcss` and load it (along with any files it imports) into the global CSS bundle.

## JavaScript

And let's not stop with just styles. We can have portable, component-specific behaviors encapsulated in JavaScript
files that live right along side the Template. Create an `index.js` file to serve as a clearing-house, loading
additional files found in the Component's `js` directory. The build system will find this `index.js` and load it
(along with anything it imports) into the global JS bundle.

## Loading the Component

Let's jump back up to the HTML output we want to render for our button:

```html
<button type="button" class="close-button" aria-label="Close">
	<span class="icon-close"></span>Close
</button>
```

We have all the code in our template and our controller to render this, but how do we include it from another template?
For this, we rely on WordPress's `get_template_part()` function. We give it the path to the component we want and the
arguments that we want to pass into the component.

```php
get_template_part( 'components/button/button', null, [
  Button_Controller::TYPE       => 'button',
  Button_Controller::CLASSES    => [ 'close-button' ],
  Button_Controller::ARIA_LABEL => __( 'Close', 'tribe' ),
  Button_Controller::CONTENT    => sprintf( '<span class="icon-close"></span>%s', __( 'Close', 'tribe' ) ),
] );
```

This will echo the rendered component to the browser. If you need to save the output to a variable instead of
echoing, use `tribe_template_part()`. Calling `echo tribe_template_part()` is exactly the same as calling
`get_template_part()`.

## Injecting Components into Other Components

Sometimes we have a component where we're passing in arbitrary content. This content might, as in our example above, be
a simple text string. Or maybe we're passing a full block into a slide, or we're putting a wrapper each link in a list.

One approach is to render the component using `tribe_template_part()`, and then pass the rendered string into its
wrapper. This is often the correct solution, but occasionally it removes some of the flexibility we need.

For these rare cases, we offer `defer_template_part()`. It's used just like `tribe_template_part()`, but instead of a
string, it will return a `Deferred_Component` object. Think of this object as analogous to Schrödinger's cat. It is
a fully rendered component, while at the same time being a flexible object that we can continue to manipulate. We can
continue to read and set arguments to the template's controller, e.g., to add class names or attributes, change
header levels, etc. Only when we finally force that `Deferred_Component` object to become a string, either
explicitly (`(string) $content`) or implicitly (`echo $content`), does it collapse into its final observable state.

Right now, you're thinking that sounds overly complicated. You're absolutely correct; 99% of the time, this is
unnecessary abstraction. For that other 1%, we'll be happy to have it around. Here's a contrived example of how we
might use it:

I have a list of links. Each of those will be rendered with a `Link` component. Each of those links is going to have
an `<li>` wrapper around it. And I'm going to pass that list of wrapped links into a widget. Depending on where that
widget displays on the site (e.g., in the sidebar or the footer), I might need different classes on those `<li>`
wrappers so they can be styled appropriately.

```php
// this will all probably be in a controller somewhere
$link_data = some_function_that_gives_me_my_link_data();

$link_components = array_map( static function( $data ) {
  return defer_template_part( 'components/link/link', null, [
    Link_Controller::URL     => $data['url'],
    Link_Controller::CONTENT => $data['label'],
  ] );
}, $link_data );

$wrapped_components = array_map( static function( $link ) {
  return defer_template_part( 'components/container/container', null, [
    Container_Controller::TAG     => 'li',
    Container_Controller::CONTENT => $link,
  ] );
}, $link_components );

// footer_widgets.php
get_template_part( 'components/link_list_widget/link_list_widget', null, [
  Link_List_Widget_Controller::LINKS   => $wrapped_components,
  Link_List_Widget_Controller::CONTEXT => 'footer',
] );

// Link_List_Widget_Controller.php
class Link_List_Widget_Controller extends Abstract_Controller {
  // ... skipping over a lot here
  public function get_links(): iterable {
    foreach ( $this->links as $link ) {
      // add a contextual class to the wrapper
      $link['classes'][] = sprintf( '%s-widget-link', $this->context );
      yield $link;
    }
  }
}

// link_list_widget.php
?>
<ul>
<?php foreach ( $c->get_links() as $link ) {
  // the Deferred_Component is finally cast to a string here, causing the container and its nested link to render
  echo $link;
} ?>
</ul>
```

## So. Much. Typing.

“Cool.” You say. “But that’s a lot of typing and PHP work to just render some HTML…why would we do this to ourselves?”

That’s a valid point. Though I would argue that a few dozen lines of PHP isn’t exactly _daunting_, it is true that this
is a decent amount of work for essentially the exact same result as, say, just using a PHP template with logic
sprinkled in. 

However, the amount of power and flexibility this gives us is, in my humble opinion, _well_ worth the learning curve.
For one, if you ever need to update the markup for Buttons across the site, you update a _single template file_ and it
flows throughout the entire site! Need to slightly modify the markup structure? Make one change, and BOOM it’s all
over your site. Need to make your Title components to all have aria labels? Make that change once, and you have it
for every single Title Component. 

Also, this allows components to display the results of very complex processes without having to muddy up the
display file. 

For instance, what if you Text component needs to do a call to an API to get the content? That requires multiple
methods for making the curl request, caching results, invalidating the cache, parsing the data, doing sanitization,
etc. Now that can all live in a dependency calling the Text component, and the Text component itself can remain
clean and simple. Separation of concerns is always a good route, and this architecture allows that.

But still…that's a lot of typing. We're developers, aren't we? What do we do with time-consuming, tedious tasks?
We automate them! Let's take a look at the component generator.

That's right. We wrote code to write our code. Next week we're working on writing a generator generator.

```
wp s1 generate component link
```

This command from the [`moderntribe/square1-generators` package from Tribe Libs](https://github.com/moderntribe/square1-generators)
will create all of the files you need to get started with a new component. Even better than copying/pasting chunks of
code from one component to another, the generator will ensure you have all the pieces you need, consistently
following the SquareOne patterns.

## Workflows
I’ve seen a lot of conversation around the correct workflow for using Components. Since they use both Controllers
(BE Dev!) and template Files (FE Dev!), who needs to do the work first? On the one hand, this can be a bit of a
chicken-egg scenario. On the other hand, since we’re completely separating the logic from the template, it also
sort of doesn’t matter! 

If the FE dev gets to it first, they can just make a static template with some test values and let the BE dev work out
the Controllers to make that a reality. Or, if they’re comfortable in PHP, they can simply add static values to the
properties in the Controller and let BE hop in later to update it with actual dynamic content. 

If BE gets to it first, they can simply follow the Data Architecture and set up the various components. Then FE can
make any tweaks they want to the existing setup and style the results. 

In the end, it all comes down to good communication. If either FE or BE are failing to communicate their needs to
the other person, the process is going to break down. Good @todo comments and quick Slack conversations are key to
making sure the process goes smoothly. 

## Components are your friend!
The entire philosophy behind Components is to provide an architecture that speeds up development and makes our work
more consistent and maintainable. However, they’re also not a one-size-fits-every-single-context solution. Sometimes,
a block really does need to have custom markup. If you ever find yourself (BE _or_ FE) struggling to shoehorn a design
into components, make a new one! Components should work for you, not the other way around. And if the new component
you made seems useful as a core SquareOne component, submit a PR! We’re always looking to improve this system. 

Now, on the other hand, if you find yourself creating a lot of custom components, it might be time for some reflection
to see why that is. Are the designs simply not aligning with how we have Components structured? That’s possible! If so
there should probably be some conversation around why that is to ensure we’re on the right path. Are you maybe missing
some ways that you actually could use Components? I’ve been surprised by how flexible the Components really are. Nine
times out of ten there’s a way to use the components library we have to accomplish any given layout. Give it a shot!
Sometimes it’s not realistic, but it often is. Just like with our old dear friend CSS Zen Garden, it’s amazing what
you can accomplish with the same markup and some creative CSS work. 

## Wrapping up
I hope this has helped clarify how Components work a bit. They can be daunting to get into, but once you start using
them they really do have a substantial impact on the quality of the final product. If you find any bugs or issues with
them, please submit a PR to SquareOne! Components are a work-in-progress, and they can always get better. 
