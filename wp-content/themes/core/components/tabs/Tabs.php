<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Tabs
 *
 * @property array[]  $tabs An array of structured tabs, each with the following keys:
 *                          - content - string - The tab content
 *                          - content_id  - string- Unique id for the content
 *                          - tab_id - string - Unique id for the tab
 *                          - tab_text - string - Text for the tab
 *                          - btn_attrs - string[] - Additional attributes for the button, passed
 *                                                   through to the button component
 *                          - content_attrs - string[] - Additional tab inner content attributes
 * @property string   $wrapper_id
 * @property string[] $container_classes
 * @property string[] $container_attrs
 * @property string[] $tab_list_classes
 * @property string[] $tab_list_attrs
 * @property string[] $tab_button_classes
 * @property string   $tab_button_active_class
 * @property string[] $tab_button_attrs
 * @property string[] $tab_button_options
 * @property string[] $tab_content_classes
 * @property string[] $tab_content_attrs
 * @property string   $tab_content_active_class
 * @property string[] $tab_content_inner_classes
 * @property string[] $tab_content_inner_attrs
 */
class Tabs extends Component {

	public const TABS                      = 'tabs';
	public const WRAPPER_ID                = 'wrapper_id';
	public const CONTAINER_CLASSES         = 'container_classes';
	public const CONTAINER_ATTRS           = 'container_attrs';
	public const TAB_LIST_CLASSES          = 'tab_list_classes';
	public const TAB_LIST_ATTRS            = 'tab_list_attrs';
	public const TAB_BUTTON_CLASSES        = 'tab_button_classes';
	public const TAB_BUTTON_ACTIVE_CLASS   = 'tab_button_active_class';
	public const TAB_BUTTON_ATTRS          = 'tab_button_attrs';
	public const TAB_BUTTON_OPTIONS        = 'tab_button_options';
	public const TAB_CONTENT_CLASSES       = 'tab_content_classes';
	public const TAB_CONTENT_ATTRS         = 'tab_content_attrs';
	public const TAB_CONTENT_ACTIVE_CLASS  = 'tab_content_active_class';
	public const TAB_CONTENT_INNER_CLASSES = 'tab_content_inner_classes';
	public const TAB_CONTENT_INNER_ATTRS   = 'tab_content_inner_attrs';

	private const TABLIST_BUTTONS = 'tablist_buttons';

	protected function defaults(): array {
		return [
			self::TABS                      => [],
			self::WRAPPER_ID                => uniqid( 'tab-', false ),
			self::CONTAINER_CLASSES         => [ 'c-tabs' ],
			self::CONTAINER_ATTRS           => [ 'data-js' => 'c-tabs' ],
			self::TAB_LIST_CLASSES          => [ 'c-tab__list' ],
			self::TAB_LIST_ATTRS            => [],
			self::TAB_BUTTON_CLASSES        => [ 'c-tab__button' ],
			self::TAB_BUTTON_ACTIVE_CLASS   => 'c-tab__button--active',
			self::TAB_BUTTON_ATTRS          => [],
			self::TAB_BUTTON_OPTIONS        => [],
			self::TAB_CONTENT_CLASSES       => [ 'c-tab__content', 's-sink t-sink' ],
			self::TAB_CONTENT_ATTRS         => [ 'role' => 'tabpanel', 'tabindex' => 0 ],
			self::TAB_CONTENT_ACTIVE_CLASS  => 'c-tab__content--active',
			self::TAB_CONTENT_INNER_CLASSES => [ 'c-tab__content-inner' ],
			self::TAB_CONTENT_INNER_ATTRS   => [],
		];
	}

	public function init() {
		$this->data[ self::CONTAINER_ATTRS ]['data-button-active-class']  = $this->data[ self::TAB_BUTTON_ACTIVE_CLASS ];
		$this->data[ self::CONTAINER_ATTRS ]['data-content-active-class'] = $this->data[ self::TAB_CONTENT_ACTIVE_CLASS ];
		$this->data[ self::TABLIST_BUTTONS ]                              = $this->get_tablist_buttons();
	}

	/**
	 * Build the buttons for the tabs, delegating to the button component
	 *
	 * @return string[]
	 */
	protected function get_tablist_buttons(): array {
		$tabs    = $this->data[self::TABS];
		$buttons = [];
		foreach ( $tabs as $index => $tab ) {
			$tab                       = wp_parse_args( $tab, [
				'content'       => '',
				'content_id'    => '',
				'tab_id'        => '',
				'tab_text'      => '',
				'btn_attrs'     => [],
				'content_attrs' => [],
			] );
			$button_attributes_default = [
				'role'           => 'tab',
				'data-js'        => 'c-tab__button',
				'id'             => esc_attr( $tab['tab_id'] ),
				'aria-controls'  => esc_attr( $tab['content_id'] ),
				'aria-selected'  => ( $index === 0 ) ? 'true' : 'false',
				'data-row-index' => $index,
			];
			$button_attributes         = array_merge( $button_attributes_default, $this->data[ self::TAB_BUTTON_ATTRS ], $tab['btn_attrs'] );
			$btn_classes               = $this->data[ self::TAB_BUTTON_CLASSES ];
			if ( $index === 0 ) {
				$btn_classes[] = $this->data[ self::TAB_BUTTON_ACTIVE_CLASS ];
			}
			$options   = [
				Button::CONTENT => $tab['tab_text'],
				Button::CLASSES => $btn_classes,
				Button::ATTRS   => $button_attributes,
			];
			$options   = wp_parse_args( $options, $tab['btn_options'] );
			$buttons[] = $this->factory->get( Button::class, $options )->get_rendered_output();
		}

		return $buttons;
	}

	public function render(): void {
		?>
		<div {{ classes( container_classes )|esc_attr }}" id="{{ tab_id|esc_attr }} {{ attributes( container_attrs ) }}>
			<div {{ classes( tab_list_classes )|esc_attr }} {{ attributes( tab_list_attrs ) }}>
				{% for button in tablist_buttons %}
					{{ button }}
				{% endfor %}
			</div>

			{% for tab in tabs %}
				<div class="{{ classes( tab_content_classes )|esc_attr }} {% if loop.index0 == 0 %}{{ tab_content_active_class|esc_attr }}{% endif %}"
					 aria-hidden="{% if loop.index0 == 0 %}false{% else %}true{% endif %}"
					 id="{{ tab.content_id|esc_attr }}"
					 aria-labelledby="{{ tab.tab_id|esc_attr }}"
					 {{ attributes( tab_content_attrs ) }}
				>
					<div {{ classes( tab_content_inner_classes ) }}
							{{ attributes( tab_content_inner_attrs ) }}
							{{ attributes( tab.content_attrs ) }}>
						{{ tab.content }}
					</div>
				</div>
			{% endfor %}
		</div>
		<?php
	}
}
