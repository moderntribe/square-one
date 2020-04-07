# SquareOne - A Guide to Components
So you’ve started using SquareOne (or, maybe, have been using it for years but like me have found yourself a bit lost when it comes to the scope of features it offers) and are seeing references to “Components” sprinkled throughout the codebase. They seem…powerful I guess? At the very least they seem _complicated_ which sometimes is a good indication that something is powerful even if it’s confusing. 

To be honest, Components are something that can take a bit of time to wrap your head around. I’m one of the main architects of the system and I still find myself scratching my head from time to time. Common sense would seem to indicate that perhaps that means we need to refactor them, but I’m a developer so common sense is not my strong suit, so instead I’ll laugh in the face of logic and double down on the system.

(In all seriousness, I really do think our Components system is worthwhile once you get a good handle on it, and I hope this guide helps assist it reaching a good comfort level with them).

So in the spirit of clarifying what can be a mystifying system, I want to walk through how Components work, and how we can use them in our code to provide better, more consistent templating for the Front End of a site. 

## So…what is this component of which you speak?
I like to think of Components as the “building blocks” of our sites. They’re the “parts” that make up a whole page, and can be as simple as a single button `<button>Click Me!</button` or as complex as an entire Card element with titles, images, text, and buttons. 

I find it helpful to think of them like one of the fancier LEGO sets. When you build the Millennium Falcon, you don’t build a different exhaust port from scratch using custom pieces every time; instead, you use the same smaller parts to build each exhaust port so they are uniform, and then you use those exhaust ports as part of the Falcon as a whole so that it in turn is uniform. Using the same basic building blocks from the smallest part to the largest component makes the entire building process more manageable and consistent. 

Components work in a similar fashion. Each page can be broken down into discrete parts, each of which is in turn made up of _other_ discrete parts. A Slider is really just made up of individual slides, which in turn are really just composed of text, images, and buttons. By breaking these things down into their own parts and re-using the underlying markup, we achieve consistent output and much more streamlined designs. 

## Oh. So cookie cutter sites then?
I mean, we definitely _could_ make cookie-cutter sites with Components, but that’s definitely not the goal. Rather than looking at Components as a set of tools for creating generic solutions for our clients, think of them as a set of tools that are generalized enough to be configured into _custom_ solutions for a client. To use the LEGO analogy from before, even the most basic 70’s LEGO set of small colorful bricks can be turned into a limitless number of creations! The advantage of LEGO isn’t that the end result always looks the same, it’s that they’ve been engineered to fit together in a consistent matter so the builder can spend less time worrying about how to stick them together and more time just creating! That’s our hope with Components. Take away the menial task of making a fresh button from scratch every time and just focus on providing an existing Button component with the data it needs to render.

## Ok, you’ve got me hooked! So how does a Component work?

Now that I’ve enticed you with talk of LEGO and creations and pie-in-the-sky aspirations of boundless imagination, let’s get down to the brass tacks of components. 

Components are really two different pieces of code. We have a *Controller*, which is a PHP file that handles gathering, parsing, and applying logic to the data for a Component, and a *Twig Template*, which is really just a specific type of HTML file that allows for things like variables and basic logic structures like loops. 

The *Controller* feeds the *Twig Template* data, and the *Twig Template* displays it for the end-user. Without a Controller, the Twig Template would just spit out empty markup. And without a Twig Template, the Controller would just toss its data into the empty void of a merciless Linux server.

For example purposes, let’s look at the Card component. Here’s what it’s Controller looks like:

```php
<?php

namespace Tribe\Project\Templates\Components;

class Card extends Context {

	const TEMPLATE_NAME = 'components/card.twig';

	const BEFORE_CARD     = 'before_card';
	const AFTER_CARD      = 'after_card';
	const TITLE           = 'title';
	const TEXT            = 'text';
	const IMAGE           = 'image';
	const PRE_TITLE       = 'pre_title';
	const POST_TITLE      = 'post_title';
	const CLASSES         = 'card_classes';
	const HEADER_CLASSES  = 'card_header_classes';
	const CONTENT_CLASSES = 'card_content_classes';
	const BUTTON          = 'button';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::BEFORE_CARD     => '',
			self::AFTER_CARD      => '',
			self::TITLE           => '',
			self::TEXT            => '',
			self::IMAGE           => '',
			self::PRE_TITLE       => '',
			self::POST_TITLE      => '',
			self::CLASSES         => [],
			self::HEADER_CLASSES  => [],
			self::CONTENT_CLASSES => [],
			self::BUTTON          => '',
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			static::BEFORE_CARD     => $this->options[ self::BEFORE_CARD ],
			static::AFTER_CARD      => $this->options[ self::AFTER_CARD ],
			static::TITLE           => $this->options[ self::TITLE ],
			static::PRE_TITLE       => $this->options[ self::PRE_TITLE ],
			static::POST_TITLE      => $this->options[ self::POST_TITLE ],
			static::CLASSES         => $this->merge_classes( [ 'c-card' ], $this->options[ self::CLASSES ], true ),
			static::HEADER_CLASSES  => $this->merge_classes( [ 'c-card__header' ], $this->options[ self::HEADER_CLASSES ], true ),
			static::CONTENT_CLASSES => $this->merge_classes( [ 'c-card__content' ], $this->options[ self::CONTENT_CLASSES ], true ),
			static::TEXT            => $this->options[ self::TEXT ],
			static::IMAGE           => $this->options[ self::IMAGE ],
			static::BUTTON          => $this->options[ self::BUTTON ]
		];

		return $data;
	}
}
```

And here’s what the Twig Template looks like:

```html
<div class="{{ card_classes }}">

	{% if before_card %}
		{{ before_card }}
	{% endif %}

	{% if image %}
		<header class="{{ card_header_classes }}">
			{{ image }}
		</header>
	{% endif %}

	<div class="{{ card_content_classes }}">

		{% if pre_title %}
			{{ pre_title }}
		{% endif %}

		{% if title %}
			{{ title }}
		{% endif %}

		{% if post_title %}
			{{ post_title }}
		{% endif %}

		{% if text %}
			{{ text }}
		{% endif %}

		{% if button %}
			{{ button }}
		{% endif %}

	</div>

	{% if after_card %}
		{{ after_card }}
	{% endif %}

</div>
```

Before we move on, let’s clean up that Twig Template a bit to remove some conditionals. They’re there mostly as safeguards against empty data, but Twig already handles that so for the time being let’s clear them out. Then we’re left with this:

```html
<div class="{{ card_classes }}">

	{{ before_card }}

	{% if image %}
		<header class="{{ card_header_classes }}">
			{{ image }}
		</header>
	{% endif %}

	<div class="{{ card_content_classes }}">

		{{ pre_title }}

		{{ title }}

		{{ post_title }}

		{{ text }}

		{{ button }}

	</div>

	{{ after_card }}

</div>
```

Pretty simple eh? So if you’re not familiar with Twig, the `{{ foo }}` syntax is simply how Twig echoes variables. So `before_card` and `title` and `button` are all just variables that the Controller is passing to the Twig file, which it then in turn is echoing. 

You’ll perhaps notice, then, that this Twig file is…really mostly just echoing variables. And you’d be correct! Components are all about injecting the data where it needs to go. A good Component simply takes what it’s given and gives it structure. In fact, that’s one of the driving principles behind Components - it separates Logic from Layout. Controllers let PHP do what it does best (calculate things) while Twig lets HTML do what it does best (display things). This system avoids the mess that can result from a PHP file mixing logic in with display. We’ve all seen this before:

```html
<ul class="my-list" <?php if ( $javascript === 'foobar' ) { echo 'data-js="' . $javascript . '"'; } ?>>
	<?php foreach ( $list_items as $index => $item ) : ?>
	<?php if ( $index === 0 ) {
		$class = 'first-thing';
	}
	?>
		<li <?php if ( ! empty( $class ) ) { echo 'class="' . $class . '"'; } ?>>
			<?php echo $item['name']; ?>
		</li>
	<?php endforeach; ?>
</ul>
```

That physically hurt me to type out. Isn’t the Component Twig markup much cleaner and easier to parse? I sure think so, but then again I did make it so I might be biased.

## The Anatomy of a Controller
Ok, so that’s what Controllers and Twig Templates look like. Now let’s take some time to go over what makes up a controller. Starting from the top of our Card controller:

```php
class Card extends Component {
```

So far so good. This is our Card Component, and it extends the base abstract class of Component. I like it!

```php
const TEMPLATE_NAME = 'components/card.twig';

const BEFORE_CARD     = 'before_card';
const AFTER_CARD      = 'after_card';
const TITLE           = 'title';
const TEXT            = 'text';
const IMAGE           = 'image';
const PRE_TITLE       = 'pre_title';
const POST_TITLE      = 'post_title';
const CLASSES         = 'card_classes';
const HEADER_CLASSES  = 'card_header_classes';
const CONTENT_CLASSES = 'card_content_classes';
const BUTTON          = 'button';
```

A bunch of constants! You’ll recognize this from all the other SquareOne code you’ve looked at. We _love_ constants. And who wouldn’t? They let us define strings right within a Class and reference those strings in other classes when needed. Goodbye hardcoding! Goodbye search-and-replace-all-instances-whoops-I-forgot-one-now-the-site-is-white-screening-time-to-update-my-resume! Constants are great. 

First we define a constant for the Template Name - this is just the Twig Template that this Controller will use to render the content. Every Component Controller _needs_ one of these constants. Without it, it is nothing. Think of this as the Garfunkle to Paul Simon. Just useless by itself. So instead we define a Twig Template to use to render our data we’re setting up here. 

Next we set up constants for all the variable names we’ll eventually pass to the Twig File. This is handy for a couple reasons - first, it allows us to use an IDE’s autocomplete when instantiating this Component (more on that later). Second, it acts as a sort of reference for what this Controller accepts as options and returns as variables. Handy! Moving on:

```php
protected function parse_options( array $options ): array {
	$defaults = [
		self::BEFORE_CARD     => '',
		self::AFTER_CARD      => '',
		self::TITLE           => '',
		self::TEXT            => '',
		self::IMAGE           => '',
		self::PRE_TITLE       => '',
		self::POST_TITLE      => '',
		self::CLASSES         => [],
		self::HEADER_CLASSES  => [],
		self::CONTENT_CLASSES => [],
		self::BUTTON          => '',
	];

	return wp_parse_args( $options, $defaults );
}
```

Here we’re just setting up sensible defaults for any options that get passed to this controller. Most are strings, but some are arrays! Others could be booleans. Any type of variable, is what I’m saying. This just ensures that if the dev doesn’t pass a specific value here, we have something as a default. We just use `wp_parse_args` to add the defaults to whatever the user passes to us in the `$options` array. More on that later! (foreshadowing!)

```php
public function get_data(): array {
	$data = [
		static::BEFORE_CARD     => $this->options[ self::BEFORE_CARD ],
		static::AFTER_CARD      => $this->options[ self::AFTER_CARD ],
		static::TITLE           => $this->options[ self::TITLE ],
		static::PRE_TITLE       => $this->options[ self::PRE_TITLE ],
		static::POST_TITLE      => $this->options[ self::POST_TITLE ],
		static::CLASSES         => $this->merge_classes( [ 'c-card' ], $this->options[ self::CLASSES ], true ),
		static::HEADER_CLASSES  => $this->merge_classes( [ 'c-card__header' ], $this->options[ self::HEADER_CLASSES ], true ),
		static::CONTENT_CLASSES => $this->merge_classes( [ 'c-card__content' ], $this->options[ self::CONTENT_CLASSES ], true ),
		static::TEXT            => $this->options[ self::TEXT ],
		static::IMAGE           => $this->options[ self::IMAGE ],
		static::BUTTON          => $this->options[ self::BUTTON ]
	];

	return $data;
}
```

And here we take all of the Options we were sent, do some minor processing on them, and return them as a `$data` array. This array holds all of the variables that the Twig Template can output. And that’s it!

“But wait!” You say. “We were promised logic! And data processing! And other PHP things! What is this, a Controller for ants?”

So that’s an astute observation! And it actually brings up another core principle of Components - they are _un-opinionated_. Components by nature don’t really _care_ what you send them, they just want to make sure it’s the right data type and display it in the Twig Template. If we had a lot of complex logic in the Controller, that’d be _very opinionated_. Suddenly we’d need to make sure to send very specific types of data to the Controller to display, and that’s not what we want! Its not a Component’s responsibility to worry about what it’s displaying, it just wants to consume what it’s given and display it in a consistent way. The responsibility of what gets passed to the Component lives in whatever class is _calling_ that component. That’s what we call “composition” within the context of a Component. The parent class, template, what-have-you deals with all the sticky data stuff and just sends the Component the end result.  Stay tuned for more on that.

## Anatomy of a Twig Template
The Twig Template is even more simple than the Controller. Remember this from before?

```html
<div class="{{ card_classes }}">

	{{ before_card }}

	{% if image %}
		<header class="{{ card_header_classes }}">
			{{ image }}
		</header>
	{% endif %}

	<div class="{{ card_content_classes }}">

		{{ pre_title }}

		{{ title }}

		{{ post_title }}

		{{ text }}

		{{ button }}

	</div>

	{{ after_card }}

</div>
```

That really is it. It echoes the variables the Controller passes to it, and does some light markup. Occasionally you may have some loops for repeating elements. Maybe an `if` statement or two. Otherwise, it’s clean, clear, and under control. 

With that in mind, let’s move on to…

## A Basic Implementation Example
Using our Card Component, let’s go over how we would actually _use_ the darned thing. 

For the sake of this example, let’s just assume this work is being done in, say, the `Page.php` controller that handles rendering any given page. In that case, the result of this Card Component would be passed to the Page’s Twig file to be rendered. I won’t go into that too much, but use your imagination to envision how that part is going to work. I’ll just focus on the actual Component part. 

Rendering a Component is essentially 3 steps: first, we set up an array of options to send _to_ the Component Controller, next we create an instance of the Component using the `factory()` method, and finally we `render()` the result. Let’s take those steps one at a time. 

First, let’s set up the options. All Component Controllers expect an `array` of options, so let’s set one up:

```php
$options = [];
```

Sweet, looking good! High fives all around. Now let’s throw some data in there. 

Remember all of the constants we set up in the Card Controller? Now is there time to shine! We can use those constants as the keys for our array, and we automatically know that they’ll be correct for that Controller. Neat!

```php
$options = [
	Card::TITLE   => 'Card Title!',
	Card::TEXT    => 'This is the card description. Fun!',
	Card::IMAGE   => '<img src="image.png" />',
	Card::BUTTON  => '<button>Click me!</button>',
	Card::CLASSES => [ 'my-card', 'my-card--cool' ],
];
```

For now we’re just focusing on the basic elements of the card: the Title, Text, Image, and Button, as well as some classes. We could also pass in pre_title, post_title, etc, but for now let’s just focus on these. 

Next, we need to get an instance of this Card using our fancy Options array. To do this, we can use the Component’s `factory()` method. This method takes a single parameter, which as luck would have it, is our `$options` array:

```php
$card = Card::factory( $options );
```

Now we have `$card`, which is an instance of the Card component, filled to the brim with the data we provided in `$options`. Anything we _didn’t_ specify in the array just gets set as the defaults that are defined in the `parse_options` method in the Controller.

Now we just need to render it!

```php
$markup = $card->render();
```

Now our `$markup` variable contains the full, rendered markup of the Twig Template, complete with all of the data we passed it. All together our code looks like:

```php
$options = [
	Card::TITLE   => 'Card Title!',
	Card::TEXT    => 'This is the card description. Fun!',
	Card::IMAGE   => '<img src="image.png" />',
	Card::BUTTON  => '<button>Click me!</button>',
	Card::CLASSES => [ 'my-card', 'my-card--cool' ],
];

$card = Card::factory( $options );

$markup = $card->render();
```

And the end result of `$markup` would look like:

```html
<div class="my-card my-card--cool">
	<header class="">
		<img src="image.png" />
	</header>

	<div class="">

		Card Title!

		This is the card description. Fun!

		<button>Click me!</button>

	</div>

</div>
```

Cool, eh? Since we didn’t pass in any other values like pre_title and post_title, those simply don’t get rendered. Also notice that the array of classes we passed in to the Component gets rendered as space-delimited strings; the Component Controllers have helper methods that magically (by which I mean very logically and orderly) convert the array of class names into that string. It can also do similar things with arrays of attributes!

So at a basic level, that’s all there is to a Component. You send a Controller the options you want to use, and then render the result, which winds up being nice, tidy, consistent markup using whatever you passed it. 

## Components Using Components
The astute amongst you may have noticed that we were passing in actual markup to the Card for image and button. The even _more_ astute amongst you may notice that we happen to have Image and Button components. Could it be that we could instead pass those components into the Card component, like some sort of nested Component sandwich???

The answer, my friends, is _oh yes,  yes we can_. 

In fact, this is the third core principle of Components! Components should always try to use other Components to compose themselves. Basically, most components can be broken into smaller components themselves! This allows for even more consistency across the board and removes any markup from PHP files. Let’s explore how to do that.

The simplest example here will be the Button component, so let’s tackle that. (The Image component is powerful, but also….pretty intense. I recommend checking it out at your leisure, but be sure to have a decent chunk of time. It’s a real whopper!)

First, let’s review our Card Component setup:

```php
$options = [
	Card::TITLE   => 'Card Title!',
	Card::TEXT    => 'This is the card description. Fun!',
	Card::IMAGE   => '<img src="image.png" />',
	Card::BUTTON  => '<button>Click me!</button>',
	Card::CLASSES => [ 'my-card', 'my-card--cool' ],
];

$card = Card::factory( $options );

$markup = $card->render();
```

So we set up the options, instantiate the Card, and render it. Cool. Now let’s replace that Button value with a Button component!

The first thing to note is that the Card Component requires what we pass it for `BUTTON` to be a `string`. So we can’t just create a Button component and pass that _class_ in. Instead, we’ll need to `render()` it and pass _that_ to the Card as a string. That’s a good principle to keep in mind - when injecting components into other components, always inject the *rendered result*, not the class itself. 

First, let’s set up our button options:

```php
$button_options = [
	Button::LABEL   => 'Submit Me!',
	Button::CLASSES => [ 'cool-button', 'cool-button__submit' ],
	Button::TYPE    => 'submit',
];
```

This should look pretty familiar. Like with Card, Button allows for other options if you want to pass them, but for now we’ll focus on the basics. I recommend taking some time to review the Button component, because it can do some really cool things! It can be a link, a true button, have a __target value, all sorts of things!

So now that we’ve set up the options, let’s get an instance:

```php
$button = Button::factory( $options );
```

Again, no surprises here. All Components follow this exact same pattern. Finally, since we need to pass a `string` to the Card, let’s get the rendered markup:

```php
$button_markup = $button->render();
```

Ok, so all told we have this:

```php
$button_options = [
	Button::LABEL   => 'Submit Me!',
	Button::CLASSES => [ 'cool-button', 'cool-button__submit' ],
	Button::TYPE    => 'submit',
];

$button = Button::factory( $options );

$button_markup = $button->render();
```

And now `$button_markup` is a string we can pass to Card! It’s going to look something like this:

```html
<button class="cool-button cool-button__submit" type="submit">
	<span class="c-btn__text">Submit Me!</span>
</button>
```

Hooray! A button! Let’s go ahead and pass our `$button_markup` to our Card Component now:

```php
$options = [
	Card::TITLE   => 'Card Title!',
	Card::TEXT    => 'This is the card description. Fun!',
	Card::IMAGE   => '<img src="image.png" />',
	Card::BUTTON  => $button_markup,
	Card::CLASSES => [ 'my-card', 'my-card--cool' ],
];
```

Easy peasy! So the sum total of our work is now:

```php
$button_options = [
	Button::LABEL   => 'Submit Me!',
	Button::CLASSES => [ 'cool-button', 'cool-button__submit' ],
	Button::TYPE    => 'submit',
];

$button = Button::factory( $options );

$button_markup = $button->render();

$options = [
	Card::TITLE   => 'Card Title!',
	Card::TEXT    => 'This is the card description. Fun!',
	Card::IMAGE   => '<img src="image.png" />',
	Card::BUTTON  => $button_markup,
	Card::CLASSES => [ 'my-card', 'my-card--cool' ],
];

$card = Card::factory( $options );

$markup = $card->render();
```

And our rendered `$markup` value will be:

```html
<div class="my-card my-card--cool">
	<header class="">
		<img src="image.png" />
	</header>

	<div class="">

		Card Title!

		This is the card description. Fun!
        
		// This is our new rendered button markup!
		<button class="cool-button cool-button__submit" type="submit">
			<span class="c-btn__text">Submit Me!</span>
		</button>

	</div>

</div>
```

Very similar to before, but now we’re using the power of Components to show the Button as well. Magic!

## So. Much. Typing.

“Cool.” You say. “But that’s a lot of typing and PHP work to just render some HTML…why would we do this to ourselves?”

That’s a valid point. Though I would argue that 15 lines of PHP isn’t exactly _daunting_, it is true that this is a decent amount of work for essentially the exact same result as, say, just using a PHP template with logic sprinkled in. 

However, the amount of power and flexibility this gives us is, in my humble opinion, _well_ worth the learning curve. For one, if you ever need to update the markup for Buttons across the site, you update a _single Twig file_ and it flows throughout the entire site! Need to slightly modify the markup structure? Make one change, and BOOM it’s all over your site. Need to make your Title components to all have aria labels? Make that change once, and you have it for every single Title Component. 

Also, this allows components to display the results of very complex processes without having to muddy up the display file. 

For instance, what if you Text component needs to do a call to an API to get the content? That requires multiple methods for making the curl request, caching results, invalidating the cache, parsing the data, doing sanitization, etc. Now that can all live in the Controller calling the Text component, and the Text component itself can remain clean and simple. Separation of concerns is always a good route, and this architecture allows that. 

Plus, once you get comfortable with Components, it becomes pretty quick to just copy/paste in chunks of code from existing examples and just update the values. In the end having a  super-consistent architecture for setting these up actually makes that _quicker_, not slower. 

## Workflows
I’ve seen a lot of conversation around the correct workflow for using Components. Since they use both Controllers (BE Dev!) and Twig Files (FE Dev!), who needs to do the work first? On the one hand, this can be a bit of a chicken-egg scenario. On the other hand, since we’re completely separating the logic from the template, it also sort of doesn’t matter! 

If the FE dev gets to it first, they can just make a static template with some test values and let the BE dev work out the Components to make that a reality. Or, if they’re comfortable in PHP, they can simply add static values to the `$options` arrays and let BE hop in later to update them with actual dynamic content. 

If BE gets to it first, they can simply follow the Data Architecture and set up the various components. Then FE can make any tweaks they want to the existing setup and style the results. 

In the end, it all comes down to good communication. If either FE or BE are failing to communicate their needs to the other person, the process is going to break down. Good @todo comments and quick Slack conversations are key to making sure the process goes smoothly. 

## Components are your friend!
The entire philosophy behind Components is to provide an architecture that speeds up development and makes our work more consistent and maintainable. However, they’re also not a one-size-fits-every-single-context solution. Sometimes, a panel really does need to be custom markup. if you ever find yourself (BE _or_ FE) struggling to shoehorn a design into components, make a new one! Components should work for you, not the other way  around. And if the new component you made seems useful as a core SquareOne component, submit a PR! We’re always looking to improve this system. 

Now, on the other hand, if you find yourself creating a lot of custom components, it might be time for some reflection to see why that is. Are the designs simply not aligning with how we have Components structured? That’s possible! If so there should probably be some conversation around why that is to ensure we’re on the right path. Are you maybe missing some ways that you actually could use Components? I’ve been surprised by how flexible the Components really are. Nine times out of ten there’s a way to use the components library we have to accomplish any given layout. Give it a shot! Sometimes it’s not realistic, but it often is. Just like with our old dear friend CSS Zen Garden, it’s amazing what you can accomplish with the same markup and some creative CSS work. 

## Wrapping up
I hope this has helped clarify how Components work a bit. They can be daunting to get into, but once you start using them they really do have a substantial impact on the quality of the final product. If you find any bugs or issues with them, please submit a PR to SquareOne! Components are a work-in-progress, and they can always get better. 
