# Twig Implementation

In-depth Twig documentation can be found at [https://twig.sensiolabs.org/doc/2.x/templates.html](https://twig.sensiolabs.org/doc/2.x/templates.html)

## Structure

There are 2 (potential) elements to a single twig partial - the twig file itself (`part.twig`, henceforth the "Partial") and the PHP Template (`Part.php`, henceforth the "Controller").
The Partial contains the actual markup. The goal is to keep as much logic as possible out of the Partial and move it to the Controller. Acceptable logic in the Partial
would be things such as empty checks and some _very_ light value-based conditionals. The logic, on the other hand, should be moved to the Controller. 

To render a Partial file using a specific Controller, the following format is used:

```
$template = new \Tribe\Project\Templates\Index( 'index.twig' );
echo $template->render();
```

By placing this within the standard `index.php` file in the theme, we can leverage WordPress's built-in templating structure while still loading our own twig system.
Once a base template is loaded using this structure, subsequent child Partials can be loaded simply using twig's `extends()` and `include()` functions.
 
For instances when a specific Partial should have it's own distinct Controller, (in the case of a Post Type single, for instance), a new Controller can be added to the
Templates directory and loaded within the PHP file instead. So for instance, if we have a `People` post type that requires a single with significantly different data
structures than a standard `Post`, you would add a `People.php` file in `Templates` and then load it as:

```
$template = new \Tribe\Project\Templates\People( 'people.twig' );
echo $template->render();
```

This would cause the People partial to load using the specific People Controller, inheriting its `$data` structure.

## Setting Up Data Structure

The Controller is responsible for collecting, parsing, and applying structure to the data model which will be passed to the Partial. Each Controller has a method 
of `get_data()` which returns an array of `key => value` pairs. This array is passed to the Partial, where each key is available as a variable. 

For instance, if you passed a `$data` variable of `[ 'title' => 'This is my Title', 'content' => 'Hey this is some great content' ]`, those values would be accessed
from within the Partial as `{{ title }}` and `{{ content }}` respectively. The key takeaway here is that each top-level element in the `$data` array is exposed to the 
partial as a variable. 

Nested values in the `$data` array can be accessed within the Partial using either a dot notation or standard array notation. For example:

`{{ post.title }}`

`{{ post['content'] }}`

It's important to structure your data in such a way that it can be called within the Partial with as little functional work as possible. A good example of this is the 
menus - in `Base.php` you can see that the menus are set up as a value in the `$data` array, and return a String Callable object. This allows the Partial to simply 
echo the menu value using `{{ menu.primary }}` and the menu is rendered as usual. Contrast this to having to call `wp_nav_menu()` within the partial itself. This leads
to cleaner Partial files as well as more manageable data structures down the road. 

## Panels in Twig

The Panels implementation is implemented in the exact same way as any other partial type. The main difference is that you'll generally want to add a distinct Controller
for each Panel, as it will require a very specific `$data` structure. You can then reference this by having a specific PHP file for each Panel within the `panels` directory
in the theme which loads the Twig Partial using its respective Controller. 

## Re-Usuable Components

New to this setup is the idea of re-usable Components. This is simply a Partial which is context-agnostic - it can be used in a Panel, Widget, or Footer, for instance,
all without altering the markup. The key to making this work is by having each Component be context-aware. This allows us to set up a distinct `$data` structure for 
each applicable context, which then gets passed to the Component. 

In order to load a component, you use the `load_component()` helper method. This method can be called in PHP or as a twig function with the same results. You pass
as an argument the path to the Twig file you wish to load, with an assumed base directory of `core/components`. For example:

`load_component( 'slider' )`
`load_component( 'social/share` )`

The Component Controller has access to a protected property of `$context`. This will by default handle the following contexts:

- Panel `panel`
- Sidebar (or widget) `sidebar`
- Body `body`
- Footer `footer`

This can then be accessed in the `get_data()` method in order to set up the data structure appropriately. 

For instance, a simple slider Component could exist within a Panel or a Sidebar Widget. Within the Controller, you would check the `$this->context` variable and
determine where the Component is currently in a Panel or the Sidebar. You could then get the data from either the Panel vars or widget vars, respectively. This data 
would then be passed to the Component, which would render its markup without having to be concered about where it's actually being rendered.

The `get_context()` method can be overidden in any child class extending the base `Component.php` Controller. This allows you to get very specific when determining
the current component context. For instance, you could determine whether the component is being used in the first or last panel. Or whether the component appears in 
the header or in the main content area. This allows your component to be truly re-usable without having to alter any markup.