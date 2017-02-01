#  Theme PostCSS

This system uses [PostCSS](http://postcss.org/). Don't worry if you are coming from sass, we have worked to make most sass features available here. The file extension is pcss, and it lives in the pcss folder in the core theme. To make your ide parse pcss correctly you have options. 

* [Atom](https://atom.io/packages/language-postcss)
* [Sublime Text](https://packagecontrol.io/packages/Syntax%20Highlighting%20for%20PostCSS)
* Php/Webstorm. [Coming soon!](https://blog.jetbrains.com/webstorm/2016/08/webstorm-2016-3-early-access-preview/#postcss) For now, select the pcss file and associate it with sass.

## Whats the same as sass?

* The same partial imports
* You can nest. Please try not to nest deeper than 4 levels unless some complex pseudo etc requires it.
* We have mixins. Only slightly different syntax. Read more [here](https://github.com/postcss/postcss-mixins)
* We have functions. Docs [here](https://github.com/andyjansson/postcss-functions). Except now they are pure js. You can even use npm modules to your hearts content! Please add your functions to `/dev_components/theme/pcss/functions.js` and check there for some reference ones.

## Whats not the same as sass?
* No extends. 
* No for loops, but you can get postcss plugins for that if you really need them.
* Vars are to be declared in the css4 syntax, not sass syntax. `:root{ --my-var: #777}` and usage: `color: var(--my-var)`

## Whats different?

* You can use lots of css4 features. We use cssnext. Reference [here](http://cssnext.io/)
* Easy aspect ratio divs. Reference [here](https://github.com/arccoza/postcss-aspect-ratio)
* Quantity queries. Reference [here](https://github.com/pascalduez/postcss-quantity-queries)

## Linting

This systems `grunt dist` task will lint your postcss. Check the .stylelintrc.json at root to see what rules have been added beyond the [standard config](https://github.com/stylelint/stylelint-config-standard).

## Table of Contents

* [PostCSS](/docs/theme/css/postcss.md)
* [Grids](/docs/theme/css/grids.md)
* [Plugins](/docs/theme/css/plugins.md)
* [Forms](/docs/theme/css/forms.md)