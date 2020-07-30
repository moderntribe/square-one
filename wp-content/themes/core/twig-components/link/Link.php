<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

/**
 * Class Link
 *
 * @property string   $url
 * @property string   $target
 * @property string   $aria_label
 * @property string[] $classes
 * @property string[] $attrs
 * @property string   $content
 * @property string   $wrapper_tag
 * @property string[] $wraper_classes
 * @property string[] $wrapper_attrs
 */
class Link extends Component {

	public const URL             = 'url';
	public const TARGET          = 'target';
	public const ARIA_LABEL      = 'aria_label';
	public const CLASSES         = 'classes';
	public const ATTRS           = 'attrs';
	public const CONTENT         = 'content';
	public const WRAPPER_TAG     = 'wrapper_tag';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const WRAPPER_ATTRS   = 'wrapper_attrs';

	protected function defaults(): array {
		return [
			self::URL             => '',
			self::TARGET          => '',
			self::ARIA_LABEL      => '',
			self::CLASSES         => [],
			self::ATTRS           => [],
			self::CONTENT         => '',
			self::WRAPPER_TAG     => '',
			self::WRAPPER_CLASSES => [],
			self::WRAPPER_ATTRS   => [],
		];
	}

	public function init() {
		if ( ! empty( $this->data[ self::WRAPPER_TAG ] ) ) {
			$this->twig_file = 'link-with-wrapper.twig';
		}

		if ( ! empty( $this->data[ self::URL ] ) ) {
			$this->data[ self::ATTRS ]['href'] = $this->data['url'];
		}

		if ( ! empty( $this->data[ self::ARIA_LABEL ] ) ) {
			$this->data[ self::ATTRS ]['aria-label'] = $this->data['aria_label'];
		}

		if ( ! empty( $this->data[ self::TARGET ] ) ) {
			$this->data[ self::ATTRS ]['target'] = $this->data['target'];
		}

		if ( ! empty( $this->data[ self::TARGET ] ) && $this->data[ self::TARGET ] === '_blank' ) {
			$this->data[ self::ATTRS ]['rel'] = 'noopener';
			$this->data[ self::CONTENT ]     .= $this->append_new_window_text();
		}
	}

	/**
	 * Appends accessibility message for links set to open in a new tab/window.
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
}
