<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Media_Text extends Context {
	public const LAYOUT            = 'layout';
	public const WIDTH             = 'width';
	public const CLASSES           = 'classes';
	public const ATTRS             = 'attrs';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MEDIA_CLASSES     = 'media_classes';
	public const MEDIA             = 'media';
	public const CONTENT_CLASSES   = 'content_classes';
	public const CONTENT           = 'content';

	protected $path = __DIR__ . '/media-text.twig';

	protected $properties = [
		self::LAYOUT            => [
			self::DEFAULT => '',
		],
		self::WIDTH         => [
			self::DEFAULT => '',
		],
		self::CLASSES           => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-panel', 'c-panel--$type' ],
		],
		self::ATTRS             => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::CONTAINER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ '$type__container' ],
		],
		self::MEDIA_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ '$type__media' ],
		],
		self::MEDIA             => [
			self::DEFAULT => '',
		],
		self::CONTENT_CLASSES   => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ '$type__content' ],
		],
		self::CONTENT           => [
			self::DEFAULT => '',
		],
	];
}
