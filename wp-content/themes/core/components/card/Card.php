<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Card
 *
 * @property string   $before_card
 * @property string   $after_card
 * @property string   $title
 * @property string   $text
 * @property string   $image
 * @property string   $pre_title
 * @property string   $post_title
 * @property string[] $card_classes
 * @property string[] $card_header_classes
 * @property string[] $card_content_classes
 * @property string   $button
 */
class Card extends Context {
	public const BEFORE_CARD     = 'before_card';
	public const AFTER_CARD      = 'after_card';
	public const TITLE           = 'title';
	public const TEXT            = 'text';
	public const IMAGE           = 'image';
	public const PRE_TITLE       = 'pre_title';
	public const POST_TITLE      = 'post_title';
	public const CARD_CLASSES    = 'card_classes';
	public const HEADER_CLASSES  = 'card_header_classes';
	public const CONTENT_CLASSES = 'card_content_classes';
	public const BUTTON          = 'button';

	protected $path = __DIR__ . '/card.twig';

	protected $properties = [
		self::BEFORE_CARD     => [
			self::DEFAULT => '',
		],
		self::AFTER_CARD      => [
			self::DEFAULT => '',
		],
		self::TITLE           => [
			self::DEFAULT => '',
		],
		self::TEXT            => [
			self::DEFAULT => '',
		],
		self::IMAGE           => [
			self::DEFAULT => '',
		],
		self::PRE_TITLE       => [
			self::DEFAULT => '',
		],
		self::POST_TITLE      => [
			self::DEFAULT => '',
		],
		self::CARD_CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-card' ],
		],
		self::HEADER_CLASSES  => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-card__header' ],
		],
		self::CONTENT_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-card__content' ],
		],
		self::BUTTON          => [
			self::DEFAULT => '',
		],
	];
}
