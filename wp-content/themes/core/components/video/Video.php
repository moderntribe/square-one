<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Templates\Component_Factory;
use Twig\Environment;

/**
 * Class Video
 *
 * @property string   $title
 * @property string   $caption_position
 * @property string   $video_url
 * @property string   $thumbnail_url
 * @property string   $play_text
 * @property string   $shim
 * @property string[] $embed_classes
 * @property string[] $container_attrs
 * @property string[] $container_classes
 * @property string[] $container_wrap_classes
 */
class Video extends Context {
	public const TITLE                  = 'title';
	public const CAPTION_POSITION       = 'caption_position';
	public const VIDEO_URL              = 'video_url';
	public const THUMBNAIL_URL          = 'thumbnail_url';
	public const PLAY_TEXT              = 'play_text';
	public const SHIM                   = 'shim';
	public const EMBED_CLASSES          = 'embed_classes';
	public const CONTAINER_ATTRS        = 'container_attrs';
	public const CONTAINER_CLASSES      = 'container_classes';
	public const CONTAINER_WRAP_CLASSES = 'container_wrap_classes';

	public const CAPTION_POSITION_CENTER = 'center';
	public const CAPTION_POSITION_BOTTOM = 'bottom';
	public const CAPTION_POSITION_BELOW  = 'below';

	protected $path = __DIR__ . '/video.twig';

	protected $properties = [
		self::TITLE                  => [
			self::DEFAULT => '',
		],
		self::CAPTION_POSITION       => [
			self::DEFAULT => self::CAPTION_POSITION_CENTER,
		],
		self::VIDEO_URL              => [
			self::DEFAULT => '',
		],
		self::THUMBNAIL_URL          => [
			self::DEFAULT => '',
		],
		self::PLAY_TEXT              => [
			self::DEFAULT => '',
		],
		self::SHIM                   => [
			self::DEFAULT => '',
		],
		self::EMBED_CLASSES          => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-video__embed' ],
		],
		self::CONTAINER_ATTRS        => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
		self::CONTAINER_CLASSES      => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-video' ],
		],
		self::CONTAINER_WRAP_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-video__wrapper' ],
		],
	];

	public function __construct( Environment $twig, Component_Factory $factory, array $properties = [] ) {
		// set defaults that require method calls
		if ( ! array_key_exists( self::SHIM, $properties ) ) {
			$properties[ self::SHIM ] = trailingslashit( get_template_directory_uri() ) . 'img/theme/shims/16x9.png';
		}
		if ( ! array_key_exists( self::PLAY_TEXT, $properties ) ) {
			$properties[ self::PLAY_TEXT ] = __( 'Play Video', 'tribe' );
		}
		parent::__construct( $twig, $factory, $properties );
	}

	public function get_data(): array {
		$this->properties[ self::CONTAINER_CLASSES ][ self::MERGE_CLASSES ][] = sprintf( 'c-video--caption-%s', $this->caption_position );

		return parent::get_data();
	}
}
