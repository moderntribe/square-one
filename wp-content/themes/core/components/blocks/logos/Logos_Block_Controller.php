<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\logos;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\content_block\Controller as Content_Block;
use Tribe\Project\Templates\Components\link\Link_Controller;

class Logos_Block_Controller extends Abstract_Controller {
	public const CLASSES = 'classes';
	public const ATTRS   = 'attrs';
	public const TITLE   = 'title';
	public const CONTENT = 'content';
	public const CTA     = 'cta';
	public const LOGOS   = 'logos';

	public array $classes;
	public array $attrs;
	public string $title;
	public string $content;
	public array $cta;
	public array $logos;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes = (array) $args[ self::CLASSES ];
		$this->attrs   = (array) $args[ self::ATTRS ];
		$this->title   = (string) $args[ self::TITLE ];
		$this->content = (string) $args[ self::CONTENT ];
		$this->cta     = (array) $args[ self::CTA ];
		$this->logos   = (array) $args[ self::LOGOS ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES => [],
			self::ATTRS   => [],
			self::TITLE   => '',
			self::CONTENT => '',
			self::CTA     => [],
			self::LOGOS   => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'c-block', 'b-logos' ],
		];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_header(): string {
		return tribe_template_part( 'components/content_block/content_block', null, [
			'tag'     => 'header',
			'classes' => [ 'b-logos__header' ],
			'layout'  => Content_Block::LAYOUT_LEFT,
			'title'   => $this->get_title(),
			'content' => $this->get_content(),
			'cta'     => defer_template_part( 'components/container/container', null, [
				'tag'     => 'p',
				'classes' => [ 'b-logos__cta' ],
				'content' => $this->get_cta(),
			] ),
		] );
	}

	private function get_title(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-logos__title', 'h3' ],
			'content' => $this->title,
		] );
	}

	public function get_content(): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-logos__description' ],
			'content' => $this->content,
		] );
	}

	public function get_cta(): Deferred_Component {
		$cta = wp_parse_args( $this->cta, [
			'text'   => '',
			'url'    => '',
			'target' => '',
		] );

		if ( empty( $cta[ 'url' ] ) ) {
			return '';
		}

		return defer_template_part( 'components/link/link', null, [
			Link_Controller::CLASSES => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			Link_Controller::URL     => $cta['url'],
			Link_Controller::TARGET  => $cta['target'],
			Link_Controller::CONTENT => $cta['text'],
		] );
	}

	public function get_logos(): Deferred_Component {
		/*
		foreach ( $logos as $logo ) {
			// Don't add a logo if there's no image set in the block.
			if ( empty( $logo[ Logos::LOGO_IMAGE ] ) ) {
				continue;
			}
			$link = wp_parse_args( $logo[ Logos::LOGO_LINK ], [
				'title'  => '',
				'url'    => '',
				'target' => '',
			] );
			$data[] = [
				'attachment' => Image::factory( (int) $logo[ Logos::LOGO_IMAGE ]['id'] ),
				'link' => [
					'content' => $link['title'],
					'url'     => $link['url'],
					'target'  => $link['target'],
				],
			];
		}
		$logo = [
			//'use_lazyload'    => true,
			//'wrapper_classes' => [ 'b-logo__figure' ],
			//'img_classes'     => [ 'b-logo__img' ],
			//'src_size'        => 'large',
			//'srcset_sizes'    => [ 'medium', 'large' ],
		];
		if ( ! empty( $link['url'] ) ) {
			//$logo['link_url']     = $link['url'];
			//$logo['link_target']  = $link['target'];
			//$logo['link_classes'] = [ 'b-logo__link' ];
			//$logo['link_attrs']   = ! empty( $link['text'] ) ? [ 'aria-label' => $link['text'] ] : [];
		}
		*/
	}
}
