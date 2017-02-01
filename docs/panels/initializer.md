# The Panel Initializer

This PHP class, located in the core plugin [here](/wp-content/plugins/core/src/Panels/Initializer.php) initializes various helper functions that individual panel registration classes may use, as well as hooking into filters from the Panel Builder plugin to add things like default fields (shared across all panel instances) or add taxonomy/p2p filters to certain queries used in the plugin.

This class also controls the view directory, which by is assigned to `/wp-content/themes/core/content/panels` inside the initializer for this project. 

Standout helper function you will use throughout the registration of panels:

### `factory( $name, $helper_text )`

`$name` **{String}** The unique panel name (the this entire group of panels).<br/>
`$helper_text` **{String}** Optional helper text string that is display ina hidden dropdown in the panel ui.

This function will create an instance of a panel for you to configure at the start of each new panel's setup. 

`$panel = $this->handler->factory( 'wysiwyg', $helper_text );`

## Panel Instance

The `$panel` instance returned by the factory above contains the following methods:

### `set_template_dir( $directory )`

`$directory` **{String}** The absolute path to the template file for this instance

Class instances in this project provide `$this->ViewFinder` but you can choose to override that path if you like. This is powered by `set_view_directories()` on the Initializer class.

### `set_label( $label )`

`$label` **{String}** The label displayed in the panel ui when editing its content.

The main panel label used in the ui as heading label.

### `set_description( $description )`

`$description` **{String}** The short description shown below the panel thumbnail.

### `set_thumbnail( $url )`

`$url` **{String}** The url of the thumbnail to use. Please use png/jpg graphics that are 640px wide and optimized.

In this project this path resolves to this [directory](/wp-content/plugins/core/assets/panels/thumbnails) and a helper function has been supplied. Just put your file in that directory and use it like so `set_thumbnail( $this->handler->thumbnail_url( 'image-name.png' ) )`

### `add_field( $name, $options )`

`$name` **{String}** The unique name for this field (to this panel instance)<br/>
`$options` **{Array}** The options array. Refer to the panel readme for available settings.

This adds a field that appears in the content section of the panels ui. If no settings field (function below) is added this distinction is not made in the ui.

### `add_settings_field( $name, $options )`

`$name` **{String}** The unique name for this setting field (to this panel instance)<br/>
`$options` **{Array}** The options array. Refer to the panel readme for available settings.

This adds a field that appears in the settings section of the panels ui. 

##Table of Contents

* [Overview](/docs/panels/README.md)
* [The Initializer](/docs/panels/initializer.md)
* [Register A Panel](/docs/panels/register.md)
