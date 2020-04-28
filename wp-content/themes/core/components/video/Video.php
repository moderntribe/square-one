<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Templates\Component_Factory;
use Twig\Environment;

/**
 * Class Video
 *
 * @property string   $tag
 * @property string[] $attrs
 * @property string[] $classes
 * @property string   $video_url
 * @property string   $video_title
 * @property string   $trigger_label
 * @property string   $trigger_position
 * @property string   $thumbnail_url
 * @property string   $shim_url
 * @property string   $caption
 */
class Video extends Context {
	public const TAG              = 'tag';
	public const CLASSES          = 'classes';
	public const ATTRS            = 'attrs';
	public const VIDEO_URL        = 'video_url';
	public const VIDEO_TITLE      = 'video_title';
	public const TRIGGER_LABEL    = 'trigger_label';
	public const TRIGGER_POSITION = 'trigger_position';
	public const THUMBNAIL_URL    = 'thumbnail_url';
	public const SHIM_URL         = 'shim_url';
	public const CAPTION          = 'caption';

	public const TRIGGER_POSITION_CENTER = 'center';
	public const TRIGGER_POSITION_BOTTOM = 'bottom';

	protected $path = __DIR__ . '/video.twig';

	protected $properties = [
		self::TAG              => [
			self::DEFAULT => 'div',
		],
		self::CLASSES          => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-video' ],
		],
		self::ATTRS            => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::VIDEO_URL        => [
			self::DEFAULT => '',
		],
		self::VIDEO_TITLE      => [
			self::DEFAULT => '',
		],
		self::TRIGGER_LABEL    => [
			self::DEFAULT => '',
		],
		self::TRIGGER_POSITION => [
			self::DEFAULT => self::TRIGGER_POSITION_BOTTOM,
		],
		self::THUMBNAIL_URL    => [
			self::DEFAULT => '',
		],
		self::SHIM_URL         => [
			self::DEFAULT => '',
		],
		self::CAPTION          => [
			self::DEFAULT => '',
		]
	];

	public function __construct( Environment $twig, Component_Factory $factory, array $properties = [] ) {
		if ( ! array_key_exists( self::SHIM_URL, $properties ) ) {
			$properties[ self::SHIM_URL ] = trailingslashit( get_template_directory_uri() ) . 'img/theme/shims/16x9.png';
		}

		if ( ! array_key_exists( self::TRIGGER_LABEL, $properties ) ) {
			$properties[ self::TRIGGER_LABEL ] = __( 'Play Video', 'tribe' );
		}

		if ( array_key_exists( self::CAPTION, $properties ) ) {
			$properties[ self::TAG] = 'figure';
		}

		parent::__construct( $twig, $factory, $properties );
	}

	public function get_data(): array {
		$this->properties[ self::CLASSES ][ self::MERGE_CLASSES ][] = sprintf( 'c-video--trigger-%s', $this->trigger_position );

		return parent::get_data();
	}
}
