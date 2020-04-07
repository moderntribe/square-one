<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Button
 *
 * @property string   $url
 * @property string   $type
 * @property string   $target
 * @property string   $aria_label
 * @property string[] $classes
 * @property string[] $attrs
 * @property string   $label
 * @property bool     $force_display
 * @property bool     $btn_as_link
 * @property string[] $inner_attributes
 */
class Button extends Context {
	public const TAG              = 'tag';
	public const URL              = 'url';
	public const TYPE             = 'type';
	public const TARGET           = 'target';
	public const ARIA_LABEL       = 'aria_label';
	public const CLASSES          = 'classes';
	public const ATTRS            = 'attrs';
	public const LABEL            = 'label';
	public const FORCE_DISPLAY    = 'force_display';
	public const BTN_AS_LINK      = 'btn_as_link';
	public const INNER_ATTRIBUTES = 'inner_attributes';

	protected $path = __DIR__ . '/button.twig';

	protected $properties = [
		self::URL              => [
			self::DEFAULT => '',
		],
		self::TYPE             => [
			self::DEFAULT => 'button',
		],
		self::TARGET           => [
			self::DEFAULT => '',
		],
		self::ARIA_LABEL       => [
			self::DEFAULT => '',
		],
		self::CLASSES          => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
		self::ATTRS            => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'rel' => 'noopener' ],
		],
		self::LABEL            => [
			self::DEFAULT => '',
		],
		self::FORCE_DISPLAY    => [
			self::DEFAULT => false,
		],
		self::BTN_AS_LINK      => [
			self::DEFAULT => false,
		],
		self::INNER_ATTRIBUTES => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function get_data(): array {
		if ( ! $this->btn_as_link || $this->target !== '_blank' ) {
			$this->properties[ self::ATTRS ][ self::MERGE_ATTRIBUTES ] = [];
		}

		$data = parent::get_data();

		if ( $data[ self::BTN_AS_LINK ] ) {
			$data[ self::TAG ]  = 'a';
			$data[ self::TYPE ] = '';
		} else {
			$data[ self::TAG ]    = 'button';
			$data[ self::URL ]    = '';
			$data[ self::TARGET ] = '';
		}

		return $data;
	}
}
