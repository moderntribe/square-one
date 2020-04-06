<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Template_Interface;
use Tribe\Project\Theme\Util;
use Twig\Environment;

abstract class Context implements Template_Interface {

	protected const DEFAULT          = 'default_value';
	protected const VALUE            = 'value';
	protected const MERGE_ATTRIBUTES = 'merge_attributes';
	protected const MERGE_CLASSES    = 'merge_classes';
	protected const DISABLE_MERGE    = 'disable_merge';

	/**
	 * @var string Default template path to be used to render this context
	 */
	protected $path = '';
	/**
	 * @var Environment
	 */
	protected $twig;
	/**
	 * @var Component_Factory
	 */
	protected $factory;

	protected $properties = [];

	/**
	 * @param Environment       $twig
	 * @param Component_Factory $factory
	 * @param array             $properties
	 */
	public function __construct( Environment $twig, Component_Factory $factory, array $properties = [] ) {
		$this->twig    = $twig;
		$this->factory = $factory;

		foreach ( $this->properties as $key => $prop ) {
			if ( array_key_exists( $key, $properties ) ) {
				// use the given value
				$this->properties[ $key ][ self::VALUE ] = $properties[ $key ];
			} elseif ( array_key_exists( self::DEFAULT, $prop ) ) {
				// use the default value
				$this->properties[ $key ][ self::VALUE ] = $prop[ self::DEFAULT ];
			} else {
				// use null value
				$this->properties[ $key ][ self::VALUE ] = null;
			}
		}
	}

	protected function merge_classes( array ...$classes ) {
		$classes = empty( $classes ) ? [] : array_merge( ... $classes );

		return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
	}

	protected function merge_attrs( array ...$attributes ) {
		$attributes = empty( $attributes ) ? [] : array_merge( ... $attributes );

		return Util::array_to_attributes( $attributes );
	}

	public function render( string $path = '' ): string {
		$path = $path ?: $this->get_path();
		if ( empty( $path ) ) {
			throw new \RuntimeException( 'Path not specified' );
		}
		try {
			return $this->twig->render( $path, $this->get_data() );
		} catch ( \Exception $e ) {
			if ( WP_DEBUG ) {
				throw $e;
			}

			return '';
		}
	}

	protected function get_path(): string {
		$path = $this->path;
		if ( strpos( $path, '/' ) === 0 ) {
			$search = array_unique( [ get_stylesheet_directory(), get_template_directory() ] );
			$path   = str_replace( $search, '', $path );
		}

		return $path;
	}

	public function disable_merge( $property ): void {
		if ( ! array_key_exists( $property, $this->properties ) ) {
			throw new \InvalidArgumentException( sprintf( 'Property %s does not exist in class %s', $property, get_class( $this ) ) );
		}
		$this->properties[ $property ][ self::DISABLE_MERGE ] = true;
	}

	public function __get( $name ) {
		if ( array_key_exists( $name, $this->properties ) ) {
			return $this->properties[ $name ][ self::VALUE ];
		}

		throw new \InvalidArgumentException( sprintf( 'Property %s does not exist in class %s', $name, get_class( $this ) ) );
	}

	public function __set( $name, $value ) {
		if ( array_key_exists( $name, $this->properties ) ) {
			return $this->properties[ $name ][ self::VALUE ] = $value;
		}
		throw new \InvalidArgumentException( sprintf( 'Property %s does not exist in class %s', $name, get_class( $this ) ) );
	}

	public function __isset( $name ) {
		return array_key_exists( $name, $this->properties ) && isset( $this->properties[ $name ][ self::VALUE ] );
	}

	/**
	 * Get the data that will be available to the template
	 *
	 * @return array
	 */
	public function get_data(): array {
		$data = [];
		foreach ( $this->properties as $key => $property ) {
			$value = $property[ self::VALUE ];
			if ( isset( $property[ self::MERGE_ATTRIBUTES ] ) && is_array( $property[ self::MERGE_ATTRIBUTES ] ) ) {
				$merge_base = empty( $property[ self::DISABLE_MERGE ] ) ? $property[ self::MERGE_ATTRIBUTES ] : [];
				$value      = $this->merge_attrs( $merge_base, $value );
			} elseif ( isset( $property[ self::MERGE_CLASSES ] ) && is_array( $property[ self::MERGE_CLASSES ] ) ) {
				$merge_base = empty( $property[ self::DISABLE_MERGE ] ) ? $property[ self::MERGE_CLASSES ] : [];
				$value      = $this->merge_classes( $merge_base, $value );
			}
			$data[ $key ] = $value;
		}

		return $data;
	}

}
