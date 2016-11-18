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

In addition to BEM we also have some additional namespacing / prefixing of certain classes to provide 
additional clarity and insight to our classes and what they do:

| Type             | Prefix         | Example                          |
| ---------------- | -------------- | -------------------------------- |
| Utility / Helper | u-             | u-clearfix, u-color-blue         |
| Layout           | l-             | l-wrapper                        |
| Theme / Skin     | t-             | t-content, t-dark, t-light    |

These are to be used as follows:

* Utility / Helper: used for globally available helpers that do single, defined things and can be safely
used anywhere
* Layout: used for any sort of globally defined layouts, consists of the general site content wrapper, but 
can also include additionally setup, globally used layouts
* Theme / Skin: used for styles that are essentially skins or themes and will typically be used in specific 
scenarios along with other helpers that should be documented (more to come on this and better define it later)
