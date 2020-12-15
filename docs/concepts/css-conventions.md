# CSS Conventions

## BEM Naming

BEM is a popular and widely used component-based development approach used to help achieve flexible and 
maintainable code. We are primarily focused on the conventions it lays out for naming our components. You 
can read about BEM and get up to speed here:

* [BEM Methodology](https://en.bem.info/methodology/)
* [Get BEM](http://getbem.com/)

## Namespacing / Prefixing

In addition to BEM we also have some additional namespacing (prefixing & suffixing) of certain classes to provide 
additional clarity and insight to our classes and what they do:

| Type             | Prefix         | Example                          |
| ---------------- | -------------- | -------------------------------- |
| Utility / Helper | u-             | u-clearfix, u-color-blue         |
| Layout           | l-             | l-wrapper, l-container           |
| Grid             | g-             | g-row, g-col, g-row--col-2       |
| Section          | s-             | s-wrapper, s-content, s-header   |
| Component        | c-             | c-card, c-video                  |
| Theme / Skin     | t-             | t-content, t-content--light      |

These are to be used as follows:

* Utility / Helper: used for globally available helpers that do single, defined things and can be safely
used anywhere
* Layout: used for any sort of globally defined layouts, consists of the general site content container, but 
can also include additionally setup, globally used layouts; used for more page-level layouts
* Grid: used for more granular layouts, typical column based grids
* Section: used for site section layer cake, think panels or general page
* Component: used for all things components and patterns, think reusable modules
* Theme / Skin: used for styles that are essentially skins or themes and will typically be used in specific 
scenarios along with other helpers that should be documented (more to come on this and better define it later)

| Type                                 | Suffix                                         | Example                          |
| ------------------------------------ | ---------------------------------------------- | -------------------------------- |
| Min-width BP declaration or modifier | -min-{breakpoint-variable-name} or --min-{...} | g-row--col-2--min-medium         |
| Max-width BP declaration or modifier | -max-{breapoint-variable-name} or --max-{...}  | g-row--col-3--max-medium         |


## Theme PostCSS

This system uses [PostCSS](http://postcss.org/). Don't worry if you are coming from sass, we have worked to make most sass features available here. The file extension is pcss, and it lives in the pcss folder in the core theme. To make your ide parse pcss correctly you have options. 

* [Atom](https://atom.io/packages/language-postcss)
* [Sublime Text](https://packagecontrol.io/packages/Syntax%20Highlighting%20for%20PostCSS)
* [Php/Webstorm](https://plugins.jetbrains.com/idea/plugin/8578-postcss-support)
* [VSCode](https://marketplace.visualstudio.com/items?itemName=ricard.PostCSS)

### Whats the same as sass?

* The same partial imports
* You can nest. Please try not to nest deeper than 4 levels unless some complex pseudo etc requires it.
* We have mixins. Only slightly different syntax. Read more [here](https://github.com/postcss/postcss-mixins)
* We have functions. Docs [here](https://github.com/andyjansson/postcss-functions). Except now they are pure js. You can even use npm modules to your hearts content! Please add your functions to `/dev_components/theme/pcss/functions.js` and check there for some reference ones.

### Whats not the same as sass?
* No extends. 
* No for loops, but you can get postcss plugins for that if you really need them.
* Vars are to be declared in the css4 syntax, not sass syntax. `:root{ --my-var: #777}` and usage: `color: var(--my-var)`

### Whats different?

* You can use lots of css4 features. We use cssnext. Reference [here](http://cssnext.io/)
* Easy aspect ratio divs. Reference [here](https://github.com/arccoza/postcss-aspect-ratio)
* Quantity queries. Reference [here](https://github.com/pascalduez/postcss-quantity-queries)
* URLs (background images, font-faces, etc) can be automatically resolved by using `resolve()` in place of `url()` and using a relative URL from the theme root. Reference [PostCSS Assets](https://github.com/borodean/postcss-assets). Example: `background: url('img/shims/16x9.png')` becomes: `background: resolve('img/shims/16x9.png')` and outputs in the compiled css as `background: url('/wp-content/themes/core/img/shims/16x9.png')`.

### Linting

This systems `gulp dist` task will lint your postcss. Check the .stylelintrc.json at root to see what rules have been added beyond the [standard config](https://github.com/stylelint/stylelint-config-standard).

## Grid System

This is a basic grid framework that should be able to power most, if not all of the layouts
within your system.

```
<div class="g-row">
    <div class="g-col">{{ component }}</div>
</div>
```

If you want to have a particular amount of columns, but wrap at a certain point:

```
<div class="g-row g-row--col-2">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
```
<div class="g-row g-row--col-3">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
```
<div class="g-row g-row--col-4">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
...

Breakpoint Options:
use helper classes on the parent to adjust your grids by viewport width:

```
<div class="g-row g-row--col-2--min-small g-row--col-4--min-full">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```

```
<div class="g-row g-row--col-2--min-small g-row--col-4">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```

Sidebar Right Layout:

```
<div class="l-weighted-left">
    <div class="c-default">Content</div>
    <div class="c-default">Sidebar</div>
</div>
```

Sidebar Left Layout:

```
<div class="l-weighted-right">
    <div class="c-default">Sidebar</div>
    <div class="c-default">Content</div>
</div>
```

To change the alignment of your columns, add helper classes to the parent:

```
<div class="g-row g-row--center">
    <div class="g-col g-col--one-third">{{ component }}</div>
</div>
```
```
<div class="g-row g-row--pull-right">
    <div class="g-col g-col--one-third">{{ component }}</div>
</div>
```

```
<div class="g-row g-row--col-2 g-row--no-gutters">
    <div class="g-col">{{ component }}</div>
    <div class="g-col">{{ component }}</div>
</div>
```
Dynamic columns: These are mostly for when you have a particular layout that calls for a column
with a defined width.

```
<div class="g-row">
    <div class="g-col g-col--one-fourth">{{ component }}</div>
</div>
```
Optional classes include: ```g-col--one-fourth, g-col--one-third, g-col--one-half, g-col--three-fourths```

## CSS "Plugins"

SquareOne is ready to go with several CSS "plugins", which are essentially directories that contain an 
independent set of variables, styles, and essentially skins for certain plugins and functionality that we 
tend to reuse and see from project to project. You will find them at `wp-content/plugins/core/assets/theme/pcss/vendor/`.

We have them for:

* Gravity Forms
* Chosen.js
* jQuery Datepicker (primarily to be used alongside Gravity Form fields)
* Slick.js

By default they are all "active" or imported into our theme's CSS. If you do not require them for your 
project, they are implemented in a way that allows you to comment them out or delete them altogether.

TODO: potentially add documentation to go over lazy embeds and such
