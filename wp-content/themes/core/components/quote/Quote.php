<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Quote
 *
 * @property string   $quote
 * @property string   $cite
 * @property string[] $classes
 * @property string[] $quote_attrs
 * @property string[] $cite_attrs
 */
class Quote extends Context {
	public const QUOTE       = 'quote';
	public const CITE        = 'cite';
	public const CLASSES     = 'classes';
	public const QUOTE_ATTRS = 'quote_attrs';
	public const CITE_ATTRS  = 'cite_attrs';

	protected $path = __DIR__ . '/quote.twig';

	protected $properties = [
		self::QUOTE       => [
			self::DEFAULT => '',
		],
		self::CITE        => [
			self::DEFAULT => '',
		],
		self::CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-quote' ],
		],
		self::QUOTE_ATTRS => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::CITE_ATTRS  => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];
}
