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
class Link extends Context {
	public const URL        = 'url';
	public const TARGET     = 'target';
	public const ARIA_LABEL = 'aria_label';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const CONTENT    = 'content';

	protected $path = __DIR__ . '/link.twig';

	protected $properties = [
		self::URL        => [
			self::DEFAULT => '',
		],
		self::TARGET     => [
			self::DEFAULT => '',
		],
		self::ARIA_LABEL => [
			self::DEFAULT => '',
		],
		self::CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS      => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::CONTENT    => [
			self::DEFAULT => '',
		],
	];

	public function get_data(): array {
		if ( $this->url ) {
			$this->properties[ self::ATTRS ][ self::VALUE ]['href'] = $this->url;
		}

		if ( $this->aria_label ) {
			$this->properties[ self::ATTRS ][ self::VALUE ]['aria-label'] = $this->aria_label;
		}

		if ( $this->target ) {
			$this->properties[ self::ATTRS ]['target'] = $this->target;
		}

		if ( $this->target === '_blank' ) {
			$this->properties[ self::ATTRS ]['rel'] = 'noopener';
			$this->properties[ self::CONTENT ][ self::VALUE ] .= $this->append_new_window_text();
		}

		return parent::get_data();
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
		] )->render();
	}
}
