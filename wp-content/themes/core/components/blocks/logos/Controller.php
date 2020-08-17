<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\blocks\logos;

use Tribe\Project\Blocks\Types\Logos\Logos;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Deferred_Component;
use Tribe\Project\Templates\Components\content_block\Controller as Content_Block;

class Controller extends Abstract_Controller {
	/**
	 * @var string[]
	 */
	private $classes;
	/**
	 * @var string[]
	 */
	private $attrs;
	/**
	 * @var array
	 */
	private $header;
	/**
	 * @var array
	 */
	public $logos;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->classes = (array) $args['classes'];
		$this->attrs   = (array) $args['attrs'];
		$this->header  = (array) $args['header'];
		$this->logos   = (array) $args['logos'];
	}

	protected function defaults(): array {
		return [
			'classes' => [],
			'attrs'   => [],
			'header'  => [],
			'logos'   => [],
		];
	}

	protected function required(): array {
		return [
			'classes' => [ 'c-block', 'b-logos' ],
		];
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function attributes(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_header( $data ): string {
		return tribe_template_part( 'components/content_block/content_block', null, [
			'tag'     => 'header',
			'classes' => [ 'b-logos__header' ],
			'layout'  => Content_Block::LAYOUT_LEFT,
			'title'   => $this->get_title(),
			'content' => $this->get_content(),
			'cta'     => $this->get_cta(),
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
			'content' => $this->title,
		] );
	}

	public function get_cta(): Deferred_Component {
		return defer_template_part( 'components/link/link', null, [
			'classes'         => [ 'a-btn', 'a-btn--has-icon-after', 'icon-arrow-right' ],
			'url'             => $args['cta']['url'],
			'target'          => $args['cta']['target'],
			'content'         => $args['cta']['text'],
			'wrapper_tag'     => 'p',
			'wrapper_classes' => [ 'b-logos__cta' ],
		] );
	}
}
