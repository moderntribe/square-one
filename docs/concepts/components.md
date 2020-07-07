# A Guide to Components

So you’ve started using SquareOne (or, maybe, have been using it for years but like me have found yourself a bit lost
when it comes to the scope of features it offers) and are seeing references to “Components” sprinkled throughout the
codebase. They seem…powerful I guess? At the very least they seem _complicated_ which sometimes is a good indication
that something is powerful even if it’s confusing. 

To be honest, Components are something that can take a bit of time to wrap your head around. I’m one of the main
architects of the system and I still find myself scratching my head from time to time. Common sense would seem to
indicate that perhaps that means we need to refactor them, but I’m a developer so common sense is not my strong suit,
so instead I’ll laugh in the face of logic and double down on the system.

(In all seriousness, I really do think our Components system is worthwhile once you get a good handle on it, and I
hope this guide helps assist it reaching a good comfort level with them).

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

We have five separate, related pieces to consider for a component. Fortunately, many of have enough fingers to count
that high, so we should be able to make it through. Here they all are in a list we can refer back to later:

1. Template
1. Context
1. Controller
1. CSS
1. JavaScript

We managed to name three of the five to start with a "C", just like "C"omponent. Truly a sign that we're on the right
track. Let's dive in, one at a time, walking through a contrived (there's that "C" again) example to showcase the
salient details. Here's the HTML we want to generate from a Button component:

```html
<button type="button" class="close-button" aria-label="Close">
	<span class="icon-close"></span>Close
</button>
```

## Twig Templates

Our Button component's directory will contain a Twig file named `button.twig`. It will looks like:

```twig
<button {{ classes }} {{ attrs }}>
	{{ content }}
</button>
```

Pretty simple eh? So if you’re not familiar with Twig, the `{{ foo }}` syntax is simply how Twig echoes variables.
So `classes` and `attrs` and `content` are all just variables that are passed to the Twig file, which it in turn
is echoing. 

You’ll perhaps notice, then, that this Twig file is…really mostly just echoing variables. And you’d be correct!
Components are all about injecting the data where it needs to go. A good Component simply takes what it’s given
and gives it structure. In fact, that’s one of the driving principles behind Components - it separates Logic from
Layout. Controllers let PHP do what it does best (calculate things) while Twig lets HTML do what it does best
(display things). This system avoids the mess that can result from a PHP file mixing logic in with display. We’ve
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

That physically hurt me to type out. Isn’t the Component Twig markup much cleaner and easier to parse? I sure think so,
but then again I did make it so I might be biased.

For more complex templates, we have the full range of Twig tags, filters, and functions available to us in our
templates. Consult the [Twig Documentation](https://twig.symfony.com/doc/3.x/) for reference.

We have also added our own extension to Twig to bring some of WordPress's escaping and internationalization tools into
our templates. See `\Tribe\Libs\Twig\Extension` for an easily skimable list of filters and functions available. This
example demonstrates both the string translation function and its filtering through `esc_html`:

```twig
<p class="comments__none">{{ __( 'Comments are closed.' )|esc_html }}</p>
```

## Contexts

At this point, you've fallen in love with Twig. No logic, no database queries, just echoing variables with a lot of
curly brackets. But…surely there's more to it than that. We still have four fingers left. For our next trick, we need to
tell Twig to render the template with our data. This is where a `Context` class comes into play.

A `Context` is a class responsible for receiving the data and passing it along to Twig for rendering. Let's take a
look at one for our Button component.

```php
<?php

namespace Tribe\Example\Templates\Components;

/**
 * Class Button
 *
 * @property string   $type
 * @property string   $aria_label
 * @property string[] $classes
 * @property string[] $attrs
 * @property string   $content
 */
class Button extends \Tribe\Project\Templates\Components\Context {
	public const TYPE       = 'type';
	public const ARIA_LABEL = 'aria_label';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const CONTENT    = 'content';

	protected $path = __DIR__ . '/button.twig';

	protected $properties = [
		self::TYPE       => [
			self::DEFAULT => 'button',
		],
		self::ARIA_LABEL => [
			self::DEFAULT => '',
		],
		self::CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS      => [
			self::DEFAULT => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::CONTENT    => [
			self::DEFAULT => '',
		],
	];

	public function get_data(): array {
		if ( $this->type ) {
			$this->properties[ self::ATTRS ][ self::VALUE ]['type'] = $this->type;
		}

		if ( $this->aria_label ) {
			$this->properties[ self::ATTRS ][ self::VALUE ]['aria-label'] = $this->aria_label;
		}

		return parent::get_data();
	}
}
```

There's a lot here, so let's unpack it one piece at a time.

```php
class Button extends \Tribe\Project\Templates\Components\Context {}
```

Our `Context` extends `\Tribe\Project\Templates\Components\Context`. This abstract base class provides much of the
functionality that we're depending on. Nearly everything else is configuration.

```php
public const TYPE       = 'type';
public const ARIA_LABEL = 'aria_label';
public const CLASSES    = 'classes';
public const ATTRS      = 'attrs';
public const CONTENT    = 'content';
```

A bunch of constants! You’ll recognize this from all the other SquareOne code you’ve looked at. We _love_ constants.
And who wouldn’t? They let us define strings right within a Class and reference those strings in other classes when
needed. Goodbye hardcoding! Goodbye search-and-replace-all-instances-whoops-I-forgot-one-now-the-site-is-white-screening-time-to-update-my-resume!
Constants are great.  

We set up constants for all the variable names we’ll eventually pass to the Twig File. This is handy for a couple
reasons - first, it allows us to use an IDE’s autocomplete when instantiating this Context (more on that later).
Second, it acts as a sort of reference for what this Context accepts as options and returns as variables. Handy!

```php
protected $path = __DIR__ . '/button.twig';
```

The `$path` tells our `Context` how to find the Twig template it is going to load. Every Component Controller _needs_
one of these constants. Without it, it is nothing. Think of this as the Garfunkle to Paul Simon. Just useless by
itself. So instead we point to a Twig Template to use to render our data we’re setting up here.

```php
protected $properties = [
    self::TYPE       => [
        self::DEFAULT => 'button',
    ],
    self::ARIA_LABEL => [
        self::DEFAULT => '',
    ],
    self::CLASSES    => [
        self::DEFAULT       => [],
        self::MERGE_CLASSES => [],
    ],
    self::ATTRS      => [
        self::DEFAULT => [],
        self::MERGE_ATTRIBUTES => [],
    ],
    self::CONTENT    => [
        self::DEFAULT => '',
    ],
];
```

The giant `$properties` array looks a little scary, but it follows a consistent pattern you'll get used to. Each of
these properties corresponds to data that should be passed to this Context when it is instantiated. Each property
has an array of configuration options, the most common of which is the default value (`self::DEFAULT`). You'll
usually see two other notable options:

`self::MERGE_CLASSES => [ 'some', 'classes' ]`: This tells the Context that it should merge the values from this array
with the values from the given parameter to build a class attribute.

`self::MERGE_ATTRIBUTES => [ 'data-js' => 'something' ]`: This tells the Context that it should merge this array with
the values from the given parameter to build a string of HTML attributes.

Jumping back up for a minute:

```php
/**
 * Class Button
 *
 * @property string   $type
 * @property string   $aria_label
 * @property string[] $classes
 * @property string[] $attrs
 * @property string   $content
 */
```

The docblock at the top of the class should mirror the `$properties` below it. The docblock itself doesn't provide any
functionality to the application, but it does give your IDE autocompletion of some magic properties when it's working
with an instance of the Context. Each of the `$properties` that we declare can also be accessed using the
`__get()` and `__set()` magic methods. E.g.:

```php
$the_aria_label = $button_context->aria_label; // get a value from the Button Context object
$button_context->aria_label = 'new label'; // set new values on the Button Context object
``` 

```php
public function get_data(): array {
    if ( $this->type ) {
        $this->properties[ self::ATTRS ][ self::VALUE ]['type'] = $this->type;
    }

    if ( $this->aria_label ) {
        $this->properties[ self::ATTRS ][ self::VALUE ]['aria-label'] = $this->aria_label;
    }

    return parent::get_data();
}
```

You won't see the `get_data()` method in all `Context` subclasses. Many of them will simply pass there data through
to their corresponding template. But if some logic needs to run to massage that data before it is rendered, this is
the place to make it happen. In our example, we're merging the `type` and `aria-label` parameters into the `attrs`
parameter so that they will all be rendered as a single attributes string (due to the `self::MERGE_ATTRIBUTES`
declaration on the `attrs` property).

*VERY IMPORTANT NOTE*: The `get_data()` method should work with the data that was given to the Context. This is not
the time to query the WordPress database. This is not the time to make an API call. This is not the time to call
`get_post()` or `is_single()` or anything else that reaches outside of the data we have available to us inside this
instance. A Context is responsible for structuring the data it has so that it is usable by the template. All of the
data should be given to it from a Controller when it is instantiated.

## Controllers

Our template echoes variables that are passed to it from the Context. Our Context structures those variables from
the data it was instantiated with. But where does that data come from in the first place? How do we know what data to
use? Why are we even rendering a Button?

All of the business logic, all of the database queries, all of the decisions about which components to render occur
in the Controller.

Imagine, if you will, a post type archive. This archive should display a dozen posts dressed up in little boxes so
they look like a grid of cards. Each of those cards will have an image, a title, an excerpt, and a "Read More" button.
How do we use Controllers to build this page using our components? Let's start at the outside and work our way down
to our button.

When WordPress loads the page, it's going to follow the [Template Hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
and load `archive.php`. There's not going to be much in this file, but it's the gateway to bigger things.

```php
echo tribe_template( \Tribe\Project\Templates\Controllers\Page\Index::class );
```

The function `tribe_template()` is responsible for finding the Controller we've asked for (`Index`, in this example)
and giving us an instance from the [DI container](./container.md). Like many Controllers, `Index` requires several
other controllers to help it complete its job of rendering our archive page. All of these other Controllers are
listed in `Index`'s constructor.

```php
public function __construct(
    Tribe\Project\Templates\Component_Factory $factory,
    Tribe\Project\Templates\Controllers\Document\Document $document,
    Tribe\Project\Templates\Controllers\Header\Subheader $header,
    Tribe\Project\Templates\Controllers\Content\Loop_Item $item
) {
    parent::__construct( $factory );
    $this->document = $document;
    $this->header   = $header;
    $this->item     = $item;
}
```

Thanks to the magic of DI autowiring, we don't have to worry too much about how to create these other Controllers, or
the Controllers they might depend on, or the Controllers they might depend on, etc. The container will sort all of that
out to give us the instances we need.

The `Index` Controller will uses these other Controllers to build up the parameters it will pass to its own Context
(probably the `Page\Index` Context), which, among other things, expects an array of rendered posts. This array will
be built using the `Loop_Item` Controller.

```php
protected function render_posts(): array {
    $posts = [];
    while ( have_posts() ) {
        the_post();
        $posts[] = $this->item->render();
    }
    rewind_posts();

    return $posts;
}
```

Look at that! It's the [WordPress Loop](https://developer.wordpress.org/themes/basics/the-loop/)! Our `Index`
Controller handles the logic of looping through The Loop, but then asks the `Loop_Item` Controller to render each post
in the global context set up by `the_post()`.

Moving down into the `Loop_Item` Controller, it's time to gather the data that we're going to send to our `Card`
Context that we want to use to render the post. We mentioned needing an image, a title, an excerpt, and a button.

Let's start with the title, because it's simple. For our title, we might use a `Text` component, because it's made of
text. In our Controller, we can get that value using normal WordPress functions. Remember that the global `$post` was
already set up in the `Index` Controller when it called `the_post()` in The Loop.

```php
$title = get_the_title();
```

All of our Controllers have a `Component_Factory` available to them that helps us instantiate our components.

```php
$title = $this->factory->get( Text::class, [
  Text::TEXT    => get_the_title(),
  Text::TAG     => 'h2',
  Text::CLASSES => [ 'item-title' ],
] )->render();
```

With this, we've rendered our title in an `<h2>` tag. Something like:

```html
<h2 class="item-title">LEGO Millennium Falcon Teardown</h2>
```

We would probably do something similar with the excerpt.

The `Image` component is a special beast that we should address. Remember earlier, when we said that a Context should
only work with the data given to it and should not make database queries. That's still true. So we're going to need
to give the `Image` component all of the data it needs to render an image. We have a special helper class for just this
purpose, called `\Tribe\Project\Templates\Models\Image`. Its static `factory()` method will help us transform an
attachment ID into an object the `Image` component can use, containing all of te data it might need for the image.

```php
$image = $this->facotry->get( Image::class, [
  Image::ATTACHMENT => Models\Image::factory( get_post_thumbnail_id() ),
  Image::SRC_SIZE   => Image_Sizes::COMPONENT_CARD,
  // and sooooo many more args we might use here
] )->render();
```

Finally, let's look at our button. Really, it's a link that looks like a button. Its markup will look something like:

```html
<a class="button-link read-more" aria-label="Read more about LEGO Millennium Falcon Teardown">Read More</a>
```

```php
$button = $this->factory->get( Link::class, [
  Link::URL        => get_the_permalink(),
  Link::CLASSES    => [ 'read-more', 'button-link' ],
  Link::ARIA_LABEL => sprintf( __( 'Read more about %s', 'tribe' ), get_the_title() ),
  Link::CONTENT    => __( 'Read More', 'tribe' ),
] )->render();
```

Similar composition of Components will take place in a variety of Controllers up and down the page hierarchy.

We should note that the Controller is not, strictly speaking, part of a Component. You will notice that the Controller
does not live in the theme's `components` directory with all of the other pieces of the Component. Sometimes we have
a 1-to-1 relationship between a Controller and the Component that it uses to render its data; sometimes a Controller
will compose several Components together.

Here is the distinction we make: Controllers manage application logic; logic belongs in the Core plugin. Components
render data; rendering belongs in the Core theme. Even though Controllers and Components are intimately intertwined,
they live in separate worlds.

A Component could be copied from one project to another, and it would give the same output for the same input,
regardless of its environment. Controllers must be handled with more delicacy, due to their entanglements with
application-specific logic.

## CSS

We could stop here and have a fully functional Component, instantiated and rendered by a Controller. But wait,
there's more! Component styles live right next to the Template and the Context inside the Component's directory.
It starts with `index.pcss`. This is a clearing-house to load additional files found in the Component's `css`
subdirectory. The build system will find this `index.pcss` and load it (along with any files it imports) into the
global CSS bundle.

## JavaScript

And let's not stop with just styles. We can have portable, component-specific behaviors encapsulated in JavaScript
files that live right along side the Template. Create an `index.js` file to serve as a clearing-house, loading
additional files found in the Component's `js` directory. Teh build system will find this `index.js` and load it
(along with anything it imports) into the global JS bundle.

## So. Much. Typing.

“Cool.” You say. “But that’s a lot of typing and PHP work to just render some HTML…why would we do this to ourselves?”

That’s a valid point. Though I would argue that a few dozen lines of PHP isn’t exactly _daunting_, it is true that this
is a decent amount of work for essentially the exact same result as, say, just using a PHP template with logic
sprinkled in. 

However, the amount of power and flexibility this gives us is, in my humble opinion, _well_ worth the learning curve.
For one, if you ever need to update the markup for Buttons across the site, you update a _single Twig file_ and it
flows throughout the entire site! Need to slightly modify the markup structure? Make one change, and BOOM it’s all
over your site. Need to make your Title components to all have aria labels? Make that change once, and you have it
for every single Title Component. 

Also, this allows components to display the results of very complex processes without having to muddy up the
display file. 

For instance, what if you Text component needs to do a call to an API to get the content? That requires multiple
methods for making the curl request, caching results, invalidating the cache, parsing the data, doing sanitization,
etc. Now that can all live in the Controller calling the Text component, and the Text component itself can remain
clean and simple. Separation of concerns is always a good route, and this architecture allows that.

But still…that's a lot of typing. We're developers, aren't we? What do we do with time-consuming, tedious tasks?
We automate them! Let's take a look at the component generator.

That's right. We wrote code to write our code. Next week we're working on writing a generator generator.

```
wp s1 generate component link --properties=url,target,aria_label,content --css --js --controller
```

This command from the [`moderntribe/square1-generators` package from Tribe Libs](https://github.com/moderntribe/square1-generators)
will create all of the files you need to get started with a new component, populating it with all of the properties
you asked for. Even better than copying/pasting chunks of code from one component to another, the generator will
ensure you have all the pieces you need, consistently following the SquareOne patterns.

## Workflows
I’ve seen a lot of conversation around the correct workflow for using Components. Since they use both Controllers
(BE Dev!) and Twig Files (FE Dev!), who needs to do the work first? On the one hand, this can be a bit of a
chicken-egg scenario. On the other hand, since we’re completely separating the logic from the template, it also
sort of doesn’t matter! 

If the FE dev gets to it first, they can just make a static template with some test values and let the BE dev work out
the Controllers to make that a reality. Or, if they’re comfortable in PHP, they can simply add static values to the
`$properties` array in the Context and let BE hop in later to update it with actual dynamic content. 

If BE gets to it first, they can simply follow the Data Architecture and set up the various components. Then FE can
make any tweaks they want to the existing setup and style the results. 

In the end, it all comes down to good communication. If either FE or BE are failing to communicate their needs to
the other person, the process is going to break down. Good @todo comments and quick Slack conversations are key to
making sure the process goes smoothly. 

## Components are your friend!
The entire philosophy behind Components is to provide an architecture that speeds up development and makes our work
more consistent and maintainable. However, they’re also not a one-size-fits-every-single-context solution. Sometimes,
a block really does need to have custom markup. If you ever find yourself (BE _or_ FE) struggling to shoehorn a design
into components, make a new one! Components should work for you, not the other way  around. And if the new component
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
