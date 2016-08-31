Attachment Helper
=================

A library for adding a file upload field to an admin meta box

Installation
-----------------

1. Place this directory in your wp-content/mu-plugins folder (create the folder if it doesn't already exist).
2. Create a PHP file in your mu-plugins folder to load MU plugins (if it doesn't already exist).
3. Add the line: `include_once( 'attachment-helper/attachment-helper.php' );`

Usage
-----------------

Wherever you would like an image upload field:

```php
$field = new \AttachmentHelper\Field( [
	'label' => __( 'Your field label' ),
	'value' => $the_current_attachment_id,
	'name'  => $the_name_of_your_form_field,
	'id'    => $the_id_of_your_form_field,
	'size'  => 'thumbnail', // the size of the preview image to display
] );
```
