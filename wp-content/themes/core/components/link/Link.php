<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Link
 *
 * @property string   $url
 * @property string   $target
 * @property string   $aria_label
 * @property string[] $classes
 * @property string[] $attrs
 * @property string   $content
 */
class Link extends Component {

	public const URL        = 'url';
	public const TARGET     = 'target';
	public const ARIA_LABEL = 'aria_label';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const CONTENT    = 'content';


	public function init() {
		if ( ! isset( $this->data[ self::ATTRS ] ) ) {
			$this->data[ self::ATTRS ] = [];
		}

		if ( isset( $this->data['url'] ) ) {
			$this->data[ self::ATTRS ]['href'] = $this->data['url'];
		}

		if ( isset( $this->data['aria_label'] ) ) {
			$this->data[ self::ATTRS ]['aria-label'] = $this->data['aria_label'];
		}

		if ( isset( $this->data['target'] ) ) {
			$this->data[ self::ATTRS ]['target'] = $this->data['target'];
		}

		if ( isset( $this->data['target'] ) && $this->data['target'] === '_blank' ) {
			$this->data[ self::ATTRS ]['rel'] = 'noopener';
			$this->data[ self::CONTENT ]      .= $this->append_new_window_text();
		}
	}

	/**
	 * Appends accessibility message for links set to open in a new window.
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function append_new_window_text(): string {
		return $this->factory->get( Text::class, [
			Text::TAG     => 'span',
			Text::CLASSES => [ 'u-visually-hidden' ],
			Text::TEXT    => __( '(Opens new window)', 'tribe' ),
		] )->get_rendered_output();
	}

	public function render(): void {
		?>
        <a {{ classes( classes ) }} {{ attributes( attrs ) }}>
            {{ content }}
        </a>
		<?php
	}
}
