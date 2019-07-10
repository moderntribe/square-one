#  Accessibility

SquareOne does it's best to lay a foundation for building themes that are accessible to all. Here are 
the guidelines we follow, which are are focused on building sites that are reachable and maintain 
the same level of functionality whether interacted with by screen reader or through the use of a keyboard:

## What Is Accessibility?

> "Web accessibility means that people with disabilities can use the Web. More specifically, Web 
> accessibility means that people with disabilities can perceive, understand, navigate, and interact 
> with the Web, and that they can contribute to the Web".
> -[W3C Web Accessibility Initiative](https://www.w3.org/WAI/intro/accessibility.php)

For us this means building sites that are accessible by ensuring:

* Semantic and well structured markup
* Keyboard interaction alternatives for all mouse-based actions as well as navigation
* A focus on building accessible forms, by accurately identifying form fields and buttons and using proper form tags
* Text-based alternatives for all images, videos, icons and SVGs
* Building functionality and/or components which are accessible to assistive technologies through the use of WAI-ARIA

## Validating Code

You should not only actively be building accessibility into your code and features, but also verifying and testing as well. Currently we utilize [tota11y](http://khan.github.io/tota11y/) for testing our theme's accessibility while we work.

## Resources

* [W3C Web Accessibility Initiative](https://www.w3.org/WAI/)
* [Section 508 Accessibility](https://www.section508.gov/)
* [WAI-ARIA Authoring Practices](http://w3c.github.io/aria/practices/aria-practices.html)
* [WebAIM](http://webaim.org/)
* [The A11Y Project](http://a11yproject.com/)

## Table of Contents

* Theme
  * [Overview](/docs/frontend/README.md)
  * [Markup and Style](/docs/frontend/markup-and-style.md)
  * [Accessibility](/docs/frontend/accessibility.md)
  * [Image Handling](/docs/frontend/images.md)
  * [Fonts](/docs/frontend/fonts.md)
  * [Icons](/docs/frontend/icons.md)
  * CSS
    * [Overview](/docs/frontend/css/README.md)
    * [PostCSS](/docs/frontend/css/postcss.md)
    * [Grids](/docs/frontend/css/grids.md)
    * [Plugins](/docs/frontend/css/plugins.md)
    * [Forms](/docs/frontend/css/forms.md)
  * Javascript
    * [Overview](/docs/frontend/js/README.md)
    * [Selectors](/docs/frontend/js/selectors.md)
    * [Events](/docs/frontend/js/events.md)
    * [Jquery](/docs/frontend/js/jquery.md)
