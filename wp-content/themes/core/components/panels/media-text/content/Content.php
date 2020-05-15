<?php

namespace Tribe\Project\Templates\Components\Panels\Media_Text;

use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Text;

/**
 * Class Content
 *
 * @property string   $title
 * @property string   $body
 * @property string   $cta_label
 * @property string   $cta_url
 * @property string   $cta_target
 * @property string   $cta_aria
 * @property string[] $classes
 * @property string[] $attrs
 */
class Content extends \Tribe\Project\Templates\Components\Context {
	public const TITLE      = 'title';
	public const BODY       = 'body';
	public const CTA_LABEL  = 'cta_label';
	public const CTA_URL    = 'cta_url';
	public const CTA_TARGET = 'cta_target';
	public const CTA_ARIA   = 'cta_aria';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';

	protected $path = __DIR__ . '/content.twig';

	protected $properties = [
		self::TITLE      => [
			self::DEFAULT => '',
		],
		self::BODY       => [
			self::DEFAULT => '',
		],
		self::CTA_LABEL  => [
			self::DEFAULT => '',
		],
		self::CTA_URL    => [
			self::DEFAULT => '',
		],
		self::CTA_TARGET => [
			self::DEFAULT => '',
		],
		self::CTA_ARIA   => [
			self::DEFAULT => '',
		],
		self::CLASSES    => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'media-text__content' ],
		],
		self::ATTRS      => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [],
		],
	];

	public function get_data(): array {
		$data = parent::get_data();

		$data[ self::TITLE ] = $this->build_title( $data[ self::TITLE ] );
		$data['cta']         = $this->build_cta( $data );

		return $data;
	}

	private function build_title( string $title ): string {
		if ( empty( $title ) ) {
			return '';
		}

		return $this->factory->get( Text::class, [
			Text::TAG  => 'h2',
			Text::TEXT => $title,
		] )->render();
	}

	private function build_cta( array $data ): string {
		return $this->factory->get( Link::class, [
			Link::URL        => $data[ self::CTA_URL ],
			Link::CONTENT    => $data[ self::CTA_LABEL ] ?: $data[ self::CTA_URL ],
			Link::TARGET     => $data[ self::CTA_TARGET ],
			Link::ARIA_LABEL => $data[ self::CTA_ARIA ],
			Link::CLASSES    => [ 'media-text__cta' ],
		] )->render();
	}

}
