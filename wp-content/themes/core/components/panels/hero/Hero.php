<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Panels;

use Tribe\Project\Templates\Components\Context;

class Hero extends Context {

	public const CLASSES    = 'classes';
	public const ATTRIBUTES = 'attrs';

	public const TITLE    = 'title';
	public const SUBTITLE = 'subtitle';
	public const CONTENT  = 'content';
	public const CTA      = 'cta';
	public const MEDIA    = 'media';

	public const SETTING_HORIZONTAL_ALIGN = 'setting_horizontal_alignment';
	public const SETTING_BGD_COLOR        = 'setting_background_color';
	public const SETTING_TEXT_COLOR       = 'setting_text_color';


	protected $path = __DIR__ . '/hero.twig';

	protected $properties = [
		self::CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'b-hero' ],
		],
		self::ATTRIBUTES  => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],

		self::TITLE       => [
			self::DEFAULT => '',
		],
		self::SUBTITLE       => [
			self::DEFAULT => '',
		],
		self::CONTENT     => [
			self::DEFAULT => '',
		],
		self::MEDIA         => [
			self::DEFAULT => '',
		],
		self::CTA         => [
			self::DEFAULT => '',
		],

		self::SETTING_BGD_COLOR => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [],
		],
	];
}
