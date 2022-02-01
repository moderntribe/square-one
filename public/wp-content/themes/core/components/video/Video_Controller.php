<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\video;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Video_Controller extends Abstract_Controller {

	public const ATTRS                   = 'attrs';
	public const CLASSES                 = 'classes';
	public const SHIM_URL                = 'shim_url';
	public const THUMBNAIL_URL           = 'thumbnail_url';
	public const TRIGGER_LABEL           = 'trigger_label';
	public const TRIGGER_POSITION        = 'trigger_position';
	public const TRIGGER_POSITION_BOTTOM = 'bottom';
	public const TRIGGER_POSITION_CENTER = 'center';
	public const VIDEO_URL               = 'video_url';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private string $shim_url;
	private string $thumbnail_url;
	private string $trigger_label;
	private string $trigger_position;
	private string $video_url;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs            = (array) $args[ self::ATTRS ];
		$this->classes          = (array) $args[ self::CLASSES ];
		$this->shim_url         = (string) $args[ self::SHIM_URL ];
		$this->thumbnail_url    = (string) $args[ self::THUMBNAIL_URL ];
		$this->trigger_label    = (string) $args[ self::TRIGGER_LABEL ];
		$this->trigger_position = (string) $args[ self::TRIGGER_POSITION ];
		$this->video_url        = (string) $args[ self::VIDEO_URL ];
	}

	public function get_classes(): string {
		$this->classes[] = sprintf( 'c-video--trigger-%s', $this->trigger_position );

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_video_url(): string {
		return esc_url( $this->video_url );
	}

	public function get_image_shim_url(): string {
		return esc_url( $this->shim_url );
	}

	public function get_image_url(): string {
		return esc_url( $this->thumbnail_url );
	}

	public function get_trigger_label(): string {
		return esc_html( $this->trigger_label );
	}

	protected function defaults(): array {
		return [
			self::ATTRS            => [],
			self::CLASSES          => [],
			self::SHIM_URL         => trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/shims/16x9.png',
			self::THUMBNAIL_URL    => '',
			self::TRIGGER_LABEL    => __( 'Play Video', 'tribe' ),
			self::TRIGGER_POSITION => self::TRIGGER_POSITION_BOTTOM,
			self::VIDEO_URL        => '',
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-video' ],
		];
	}

}
