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

	public function render_header( $args ): string {
		return tribe_template_part( 'components/content_block/content_block', null, [
			'tag'     => 'header',
			'classes' => [ 'b-logos__header' ],
			'layout'  => Content_Block::LAYOUT_LEFT,
			'title'   => $this->get_title( $args ),
			'content' => $this->get_content( $args ),
			'cta'     => $this->get_cta( $args ),
		] );
	}

	private function get_title( $args ): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-logos__title', 'h3' ],
			'content' => $args['title'],
		] );
	}

	public function get_content( $args ): Deferred_Component {
		return defer_template_part( 'components/text/text', null, [
			'classes' => [ 'b-logos__description' ],
			'content' => $args['content'],
		] );
	}

	public function get_cta( $args ): Deferred_Component {
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
