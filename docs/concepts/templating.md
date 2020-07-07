# Templating in SquareOne

Templating in SquareOne has been modeled after typical MVC (Model, View (Or Component, in our case), Controller) patterns found in other popular PHP frameworks such as Laravel, Symfony, and CakePHP.

But what, you ask, are Models, Views, and Controllers? I'm glad you asked! Here's a quick rundown:

### Models

**Models** are encapsulations around an object you would nab from the Database. The most commonly-used Model in this system is the Post. This Model takes a WP_Post object, and wraps it in a piece of 
architecture that provides some helpful functionality for accessing, mutating, and modifying the data held within. 

Consider the way a WP_Post object (here `$my_post`) is typically handled. Let's say you have a template in which you'd like to display a post's title, content, and permalink. That'd usually look like this:

```php
<?php
setup_postdata( $my_post );

$title = get_the_title( $my_post->ID );
$content = get_the_content() // can't pass a post ID here, so we have to rely on WP's global functionality.
$permalink = get_the_permalink( $my_post->ID );

wp_reset_postdata();

?>
```

That's a lot of work to grab information from a single object, but if you want to respect any filters on the data, you have to follow these conventions. 

For a single page, that's not so bad! But once you start having to repeat this ad nauseum for a dozen templates, it starts to become a real spaghetti mess, except 
not as delicious. 

This is where Models come in. The Post model, for instance, takes the WP_Post object and wraps things like getting the filtered title, content, and featured image in
methods that can be called directly on the Model itself. So now, anywhere in your codebase you want to echo a post's title, for instance, you can simply throw out:

```php
echo $my_post_model->title();
```

And voila! You'll get the filtered post title, right as rain! But wait, there's more! Say you need to append the word "Huzzah!" to every post title. All you need to do is 
update the Post modele's `title` method to append that string, and bingo bango, there it is every time you echo the title. Neat!

### Components (Views!)

**Components**, or what would typically be called Views in other systems, are discrete, modular, agnostic pieces of code that take some data and spit out markup. 
Components don't much care where they're used, and will return consistent markup every time they're called. They're un-opinionated - the data that populates them is passed _into_
the Component, not gathered by the Component itself. Consider the Component the Honey Badger of SquareOne - it doesn't give two hoots where the data is coming from, 
it simply wants to take that data and show it off to the world. 

Everything that gets rendered on a page is a Component. Page? That's a component? The `<head>` in the HTML Document? Yep, component. The little Donate Now button that 
you put in all your sidebars in a vain attempt to trick people into contributing to your vacation fund? You _know_ that's a component. 

But components don't just live in isolation - any component can compose _other_ components right there within its markup! This way, all the markup across various pages 
can stay consistent and clean, even when content numbers start skyrocketing. 

Each component has its own, self-contained CSS and Javascript, too, so the JS to open and close your Accordion Component will always live right next to the CSS to style it, 
nestled safely in the loving arms of the PHP file that renders it. 

### Controllers

If Models are the data containers for your content, and Components are the discrete pieces of markup, then Controllers are the Maestro who uses them to compose a 
delightful HTML symphony. 

Controllers respond to either a specific page Route, or to a core PHP template from within the Theme (think page.php, single.php, index.php, etc.). That piece calls a 
function on the Controller, which gathers any data it needs, determines which top-level component to load, and then kicks off the rendering of that component. 

From there, it's ~~turtles~~ components all the way down, passing data to each subsequent component and rendering the page.

## Index Loop: A Templating Example

Models, Components, and Controllers are wonderful in theory, but what do they actually look like _in practice_?

To answer that, I invite you to take my hand and follow me on a journey deep into the foundation of blogs across the webisphere: the Index Loop.

### Step 1: GET TO THE CHOPPAH

Before our system kicks in, we let WordPress perform its typical template loading/routing. While SquareOne has a Router built-in, that layer is considered opt-in, 
and it's typically easiest to just let the core files in the theme handle routing for you. 

In our case, we're hitting the Index Loop, so WordPress is going to hand-pick the trusty `index.php` file from our theme and include it. 

Let's take a look at what `index.php` holds for us:

```php
tribe_controller( \Tribe\Project\Controllers\IndexController::class, 'loop' );
```

Well look at that! That's about the tiniest Index file I've ever seen! That's because in SquareOne, all the bootstrapping of content is handled by a Controller. 

In this case, we use the helper method `tribe_controller()` to tell the system, "Hey friend, load on up my IndexController and execute the `loop` method, please!"

And that's really it! The hard work is over for the `index.php`, and it may rest its virtual head knowing that it has done a great job. Way to go, `index.php`!

### Step 2: Taking Control(ler)

Now the IndexController is ready to step up to the plate and kick a field goal. Our `index.php` file called the `loop` method, so that's what we'll look at first:

```php
public function loop() {
    $args = [
        'main'     => $this->get_main_content(),
        'masthead' => $this->get_masthead_content(),
        'sidebar'  => $this->get_sidebar_content(),
        'footer'   => $this->get_footer_content(),
    ];

    $this->render_component( 'document/Document.php', $args );
}
```

Well boy howdy, another small method! That's great! This method really just does two things:

1. Gather together any arguments specific to the route we're loading.
2. Pass those to the Component we want to render, and render it!

That's really it! The only decision you need to make in a Controller is this: "What data do I need to gather here to pass to my Components?"

Remember, all components need to be agnostic - that means any data that needs to _change_ for them should be passed to them. Components can make decisions about stuff 
that only they care about (for instance, the Footer can gather all the copyright links itself, those will always be the same). But for things like, say, Page Title, 
the component needs to be able to render whatever you pass it, since it'll be different on every page or post. That's a great place for the Controller to step in - 
it's aware of the context (remember, we called this `loop` method specifically from our `index.php`, so we _know_ we're in the Index Loop). So your Controller should 
make those decisions and gather any data specific to its own context. 

Before we move on, let's take a quick peek at one of the methods being called here to see what types of data we're going to be passing to our Document component. 
Let's look at `get_footer_content()`. 

```php
protected function get_footer_content() {
    $menu = new Menu();

    return [
        'navigation' => [
            'menu' => $menu->secondary(),
        ],
    ];
}
```

Ooooh now _that's_ interesting. We're not returning a string to be rendered, but an array with its own arguments. Why would we do that? Have we lost our minds??

Yes, but this also has a good reason: the array of data we're returning is going to be used by the Document component to call, in turn, the Footer component. Since it's
rendering a component, and not just echoing something, we need to pass the _arguments that that component needs in order to function_. 

This is, perhaps, the biggest hill to climb in our entire Templating system. Since Components compose other Components, we need to ensure that the data we pass to them to
_do_ that rendering is an array of arguments for that component. It can be a little tricky to wrap your head around, but the basic rule of thumb is this: If your component
is composing others, you're going to need to pass it an array of arguments for that component. Anything it simply echos can be passed as a string (or in the case of classes 
and attributes, an array of values). 

### Step 3: It's Component Time!

Now that we've gathered our top-level data and kicked off the render of the Document component, we live entirely in Component land! From here, everything that renders, 
from page content to post lists to buttons to sidebars, is a component. We no longer need to call other Controllers or worry about any other routing. We've started the Components 
train, and all we have to do is hop aboard!

Let's look at the Document component:

```php
class Document extends Component {

	public function init() {
		$this->data['language_attributes'] = $this->get_language_attributes();
		$this->data['body_class']          = $this->get_body_class();
	}

	protected function get_language_attributes() {
		ob_start();
		language_attributes();

		return ob_get_clean();
	}

	protected function get_body_class() {
		return implode( ' ', get_body_class() );
	}

	public function render(): void {
		?>
		<html {{ language_attributes }}>

			{{ component( 'head/Head.php' ) }}

			<body class="{{ body_class }}">

				{{ do_action( 'tribe/body_opening_tag') }}

				<div class="l-wrapper" data-js="site-wrap">

					{{ component( 'header/masthead/Masthead.php', masthead ) }}

					{{ component( 'main/Main.php', main ) }}

					{{ component( 'sidebar/Sidebar.php', sidebar ) }}

					{{ component( 'footer/site-footer/Site_Footer.php', footer ) }}

				</div><!-- .l-wrapper -->

				{{ do_action( 'wp_footer' ) }}

			</body>

		</html>
		<?php
	}
}
```

You'll notice a few things. First and foremost, there's that awesome `render()` method. The most-keen amongst you might also notice that hey, that loolks like Twig markup!

And that, dear friend, is because it is! The `render()` method simply echos the Twig markup for this component. Any items passed to the `$data` property for this Component (either
by passing them in as `$args` by another component, or by setting them in this class) will automatically get parsed and be made available as top-level variables within
the Twig markup. We'll come back to this in a second. 

The second thing you might see is the `init()` method. This optional method can be used to gather any additional data you need for this Component that wasn't passed 
to it from another Component. For instance, here in the Document we grab the `language_attributes` and `body_class` values. These values are completely agnostic of the
current context, so only the Document component needs to care about them. As such, we don't need to bother our Controller with gathering them and passing them to us, 
the Document can just store them deep inside and rest assured knowing it'll always have what it needs. By assigning them to values inside the `$data` array property, they 
become variables that can be output in the Twig markup. 

Back to the Twig business - let's review it:

```twig
<html {{ language_attributes }}>

    {{ component( 'head/Head.php' ) }}

    <body class="{{ body_class }}">

        {{ do_action( 'tribe/body_opening_tag') }}

        <div class="l-wrapper" data-js="site-wrap">

            {{ component( 'header/masthead/Masthead.php', masthead ) }}

            {{ component( 'main/Main.php', main ) }}

            {{ component( 'sidebar/Sidebar.php', sidebar ) }}

            {{ component( 'footer/site-footer/Site_Footer.php', footer ) }}

        </div><!-- .l-wrapper -->

        {{ do_action( 'wp_footer' ) }}

    </body>

</html>
```

There are basically 2 types of output happening here. The data we simply need to echo (like the `body_class`) gets echoed as a standard Twig variable using `{{}}`. 

The second type, however, deals with all the `component()` calls you see scattered throughout. These, as you may have guessed, in turn render _another_ component, and pass 
along any arguments to them that are needed. 

Let's look at the Masthead: 

```twig
{{ component( 'header/masthead/Masthead.php', masthead ) }}
```

The `component()` method takes 2 arguments: the Component path, and the arguments to pass through. We want to load the Masthead component, so we pass in the path to that
component (keeping the `.php` extension in place!). Second, we have a bunch of arguments we need to pass to the Masthead component so it can render things. Here we pass 
`masthead`. 

But wait - where did that `mastead` value come from? We didn't set it in `init()`...

And here we see our first example of the cascading Component system in play. If you were to do a dump of the `$data` property before rendering, you'd see that it has a 
`masthead` value, which we can then use as a variable in the Twig markup. Thgt value, as it happens, comes from none other than our Index Controller! Check it out if you 
don't believe me:

```php
public function loop() {
    $args = [
        'main'     => $this->get_main_content(),
        'masthead' => $this->get_masthead_content(),
        'sidebar'  => $this->get_sidebar_content(),
        'footer'   => $this->get_footer_content(),
    ];

    $this->render_component( 'document/Document.php', $args );
}
```

See? Since we passed `masthead` as a value in the top-level arguments array, it becomes a variable available to the Document component! It'll contain anything we put 
in that element, which in turn will become variables for the Mastead component itself!

The Masthead can then render a Header Component, which in turn can render a Navigation Component, which in turn can render Link Components, and so on and so on, each component 
passing arguments to the next. 


## So in Summary

At a basic level, those 3 steps are all that happens on any given page load: a file calls a Controller method, which gathers data and passes it to a top-level component, 
which in turn renders and passes data to components of its own. This chain continues until we have a fully-working page!

There are other elements at play here, such as Models for the individual Posts, etc, but those are all secondary to the main pattern at play. 

Once you wrap your head around the idea of passing arguments down a chain, it all becomes pretty straightforward, and you'll be building loops and pages and footers 
and contact forms quicker than you can say "Wait where did this variable come from again?"
