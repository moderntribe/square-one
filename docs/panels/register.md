# Register a Panel

Panels are registered in individual class files per panel located in [this directory](/wp-content/plugins/core/src/Panels/Types). They extend the abstract class Panel_Type_Config also located in this same directory.

An example of registering a panel that contains a repeater of wysiwyg fields with a maximum of 3 allowed:

```php
namespace Tribe\Project\Panels\Types;

use ModularContent\Fields;

class Wysiwyg extends Panel_Type_Config {

	const NAME = 'wysiwyg';

	const FIELD_LAYOUT              = 'layout';
	const FIELD_LAYOUT_OPTION_LEFT  = 'left';
	const FIELD_LAYOUT_OPTION_RIGHT = 'right';
	const FIELD_COLUMNS             = 'columns';
	const FIELD_COLUMN              = 'column';

	protected function panel() {

		$helper_text = __( 'A simple wyiwyg panel that lets you free form', 'tribe' );
		$panel       = $this->handler->factory( self::NAME, $helper_text );

		$panel->set_template_dir( $this->ViewFinder );
		$panel->set_label( __( 'WYSIWYG Editor', 'tribe' ) );
		$panel->set_description( __( 'Displays custom content', 'tribe' ) );
		$panel->set_thumbnail( $this->handler->thumbnail_url( 'module-wysiwyg.png' ) );

		// Panel Layout
		$panel->add_settings_field( $this->handler->field( 'ImageSelect', [
			'name'    => self::FIELD_LAYOUT,
			'label'   => __( 'Layout', 'tribe' ),
			'options' => [
				self::FIELD_LAYOUT_OPTION_RIGHT => $this->handler->layout_icon_url( 'module-imagetext-right.png' ),
				self::FIELD_LAYOUT_OPTION_LEFT  => $this->handler->layout_icon_url( 'module-imagetext-left.png' ),
			],
			'default' => self::FIELD_LAYOUT_OPTION_RIGHT,
		] ) );

		/** @var Fields\Repeater $group */
		$group = $this->handler->field( 'Repeater', [
			'label'            => __( 'Columns', 'tribe' ),
			'name'             => self::FIELD_COLUMNS,
			'min'              => 1,
			'max'              => 3,
			'new_button_label' => __( 'Add Column', 'tribe' )
		] );

		$group->add_field( $this->handler->field( 'TextArea', [
			'label'    => __( 'Column', 'tribe' ),
			'name'     => self::FIELD_COLUMN,
			'richtext' => true
		] ) );

		$panel->add_field( $group );

		return $panel;
	}
}
```

Also note in this field that a "settings" field was added. The panel ui is separated into content and settings as soon as you use even one of these. This is makes the panel separate content and complex settings thereby reducing clutter. 

Please use the helper function `$this->handler->field( 'FIELD_NAME', [...options] )` to add a field. 

Refer to `/wp-content/plugins/panel-builder/readme.md#fields` for a list of all field types and their behavior.

##Table of Contents

* [Overview](/docs/panels/README.md)
* [The Initializer](/docs/panels/initializer.md)
* [Register A Panel](/docs/panels/register.md)