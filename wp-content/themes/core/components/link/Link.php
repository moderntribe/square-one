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
 * @property string   $text
 */
class Link extends Context {
	public const URL        = 'url';
	public const TARGET     = 'target';
	public const ARIA_LABEL = 'aria_label';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const PREPEND    = 'prepend';
	public const TEXT       = 'body';
	public const APPEND     = 'append';

	protected $path = __DIR__ . '/link.twig';

	protected $properties = [
		self::URL     => [
			self::DEFAULT => '',
		],
		self::TARGET  => [
			self::DEFAULT => '',
		],
		self::ARIA_LABEL => [
			self::DEFAULT => '',
		],
		self::CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::PREPEND => [
			self::DEFAULT => '',
		],
		self::TEXT    => [
			self::DEFAULT => '',
		],
		self::APPEND  => [
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
		}

		return parent::get_data();
	}
}
