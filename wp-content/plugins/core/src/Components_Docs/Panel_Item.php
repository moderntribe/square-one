<?php

namespace Tribe\Project\Components_Docs;

use ModularContent\Panel;
use Tribe\Project\Components_Docs\Templates\Preview_Wrapper;
use Tribe\Project\Templates\Components\Component;

class Panel_Item extends Item {

	/**
	 * @var string $item_class
	 */
	protected $item_class;

	/**
	 * @var \ReflectionClass $reflection
	 */
	protected $reflection;

	/**
	 * @var Panel $panel
	 */
	protected $panel;

	public function __construct( string $item_class, Panel $panel ) {
		if ( ! class_exists( $item_class ) ) {
			throw new \InvalidArgumentException( 'Provided panel class does not exist.' );
		}

		$this->item_class = $item_class;
		$this->reflection = new \ReflectionClass( $item_class );
		$this->panel      = $panel;
	}

	public function get_slug(): string {
		return $this->panel->get( 'type' );
	}

	public function get_label(): string {
		$vars = $this->panel->get_template_vars();
		return $vars['title'] ?? $this->panel->get( 'label' );
	}

	public function get_constants(): array {
		return [];
	}

	public function get_sales_docs(): string {
		$short_name = $this->get_slug();
		$path       = $this->get_home_path() . 'docs/sales/panels';
		$docs_name  = sprintf( '%s/%s.md', $path, strtolower( $short_name ) );

		if ( ! file_exists( $docs_name ) ) {
			return '';
		}

		$contents  = file_get_contents( $docs_name );
		$parsedown = new Parsedown();
		$rendered  = $parsedown->text( $contents );

		return $rendered;
	}

	public function get_dev_docs(): string {
		$short_name = $this->get_slug();
		$path       = $this->get_home_path() . 'docs/panels/default';
		$docs_name  = sprintf( '%s/%s.md', $path, strtolower( $short_name ) );

		if ( ! file_exists( $docs_name ) ) {
			return '';
		}

		$contents  = file_get_contents( $docs_name );
		$parsedown = new Parsedown();
		$rendered  = $parsedown->text( $contents );

		return $rendered;
	}

	public function get_twig_src(): string {
		$twig_template = sprintf( '%s/content/panels/', get_template_directory(), $this->item_class::NAME );

		if ( ! file_exists( $twig_template ) ) {
			return '';
		}

		return file_get_contents( $twig_template );
	}

	public function get_rendered_template( $options = [] ): string {
		return $this->cleanup_html( $this->panel->render() );
	}

	public function get_class_name(): string {
		return $this->item_class;
	}
}