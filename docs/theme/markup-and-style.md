#  Markup and Style

Square One is built to assist developers in creating a codebase that maintains consistency, 
flexibility, and reusabilty while adhering to CSS & HTML best practices and conventions.

To help us in accomplishing this goal, we utilize a combination of the [Block Element Modifier (BEM)](https://en.bem.info/methodology/) 
methodology along with some custom namespacing/prefixing to provide clarity, insight, and align our 
developers on the same page.

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

## Table of Contents

* Theme
  * [Overview](/docs/theme/README.md)
  * [Markup and Style](/docs/theme/markup-and-style.md)
  * [Accessibility](/docs/theme/accessibility.md)
  * [Image Handling](/docs/theme/images.md)
  * [Fonts](/docs/theme/fonts.md)
  * [Icons](/docs/theme/icons.md)
  * CSS
    * [Overview](/docs/theme/css/README.md)
    * [PostCSS](/docs/theme/css/postcss.md)
    * [Grids](/docs/theme/css/grids.md)
    * [Plugins](/docs/theme/css/plugins.md)
    * [Forms](/docs/theme/css/forms.md)
  * Javascript
    * [Overview](/docs/theme/js/README.md)
    * [Code Splitting](/docs/theme/js/code-splitting.md)
    * [Polyfills](/docs/theme/js/polyfills.md)
    * [Selectors](/docs/theme/js/selectors.md)
    * [Events](/docs/theme/js/events.md)
    * [Jquery](/docs/theme/js/jquery.md)
    * [React Apps](/docs/theme/js/react-apps.md)
