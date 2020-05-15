<?php

namespace Tribe\Project\Templates\Components\Panels\Media_Text;

/**
 * Class Embed
 *
 * @property string   $embed
 * @property string[] $classes
 * @property string[] $attrs
 */
class Embed extends \Tribe\Project\Templates\Components\Context {
	public const EMBED   = 'embed';
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';

	protected $path = __DIR__ . '/embed.twig';

	protected $properties = [
		self::EMBED   => [
			self::DEFAULT => '',
		],
		self::CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'media-text__media', 'media-text__embed' ],
		],
		self::ATTRS   => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

}
