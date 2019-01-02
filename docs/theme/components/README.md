#  Components Overview

SquareOne uses twig components to compose most of the front end UI. By components, we mean modular blocks of twig powered HTML that take an array of arguments to make them usable for any case where you need that content type. You can see the list of currently enabled component templates in the TOC below.

There is a large amount of example data and documentation available to you here to help you understand the usage as it pertains to this system, but we'll start with some basic principles of these components.

* Use components everywhere you can, you should be able to cover the majority of your UI with them. Printing text or HTML in a panel or widget? Use the text component. Need images in a slider, or in a post, or in a panel? Use the image component. 
* Components will not be used directly in your templates, rather their controllers will be used by parent template controllers in most cases.
* Controllers/panels registration are a backend task now. You may need to, at times, go in to add/modify arguments or setup some logic when a BE dev is not available. Ultimately, however, they are not a front end devs responsibility and are better suited to the backend.
* This means you will be working more with your backend devs supplying requirements when assessing your front end tickets, in tandem with strategy.
* These are not the final components. If you need to add functionality to them, and you see it as worth going back into SquareOne, please open a PR or at least communicate that to a project lead/in the channel. We are sorting a more formal process for this now.
* On that point, every time you see another valid candidate for a component consider bringing it back to SquareOne, or making project leads aware of it so they can do so.

## Table of Contents

* [Overview](/docs/theme/components/README.md)
* [Accordion](/docs/theme/components/accordion.md)
* [Button](/docs/theme/components/button.md)
* [Card](/docs/theme/components/card.md)
* [Content Block](/docs/theme/components/content_block.md)
* [Quote](/docs/theme/components/quote.md)
* [Slider](/docs/theme/components/slider.md)
* [Template](/docs/theme/components/template.md)
* [Text](/docs/theme/components/text.md)