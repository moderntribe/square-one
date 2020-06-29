<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Search
 *
 * @property string[] $form_classes
 * @property string[] $form_attrs
 * @property string[] $label_classes
 * @property string[] $label_attrs
 * @property string   $label_text
 * @property string[] $input_classes
 * @property string[] $input_attrs
 * @property string   $submit_button
 */
class Search extends Component {

	public const FORM_CLASSES  = 'form_classes';
	public const FORM_ATTRS    = 'form_attrs';
	public const LABEL_CLASSES = 'label_classes';
	public const LABEL_ATTRS   = 'label_attrs';
	public const LABEL_TEXT    = 'label_text';
	public const INPUT_CLASSES = 'input_classes';
	public const INPUT_ATTRS   = 'input_attrs';
	public const SUBMIT_BUTTON = 'submit_button';

	public function init() {
		$form_attrs = [
			'role'   => 'search',
			'method' => 'get',
			'action' => esc_url( get_home_url() ),
		];

		$label_attrs = [
			'for' => 's',
		];

		$input_attrs = [
			'type' => 'text',
			'id'   => 's',
			'name' => 's',
		];

		$this->data[ self::FORM_CLASSES ]  = $this->merge_classes( [ 'c-search' ] );
		$this->data[ self::FORM_ATTRS ]    = $this->merge_attrs( $form_attrs );
		$this->data[ self::LABEL_CLASSES ] = $this->merge_classes( [ 'c-search__label' ] );
		$this->data[ self::LABEL_ATTRS ]   = $this->merge_attrs( $label_attrs );
		$this->data[ self::INPUT_CLASSES ] = $this->merge_classes( [ 'c-search__input' ] );
		$this->data[ self::INPUT_ATTRS ]   = $this->merge_attrs( $input_attrs );
		$this->data[ self::SUBMIT_BUTTON ] = $this->submit_button();
		$this->data[ self::LABEL_TEXT ]    = __( 'Search', 'tribe' );
	}

	protected function submit_button(): array {
		$btn_attrs = [
			'name'  => 'submit',
			'value' => __( 'Search', 'tribe' ),
		];

		$options = [
			Button::CONTENT => __( 'Search', 'tribe' ),
			Button::CLASSES => [ 'c-button' ],
			Button::TYPE    => 'submit',
			Button::ATTRS   => $btn_attrs,
		];

		return $options;
	}

	public function render(): void {
		?>
        <form {{ form_classes }} {{ form_attrs }}>

            <label {{ label_classes }} {{ label_attrs }}>{{ label_text }}</label>
            <input {{ input_classes }} {{ input_attrs }}/>

            {{ component( 'button/Button.php', submit_button ) }}

        </form>
		<?php
	}
}
