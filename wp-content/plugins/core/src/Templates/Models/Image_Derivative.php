<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

/**
 * Class Image_Derivative
 *
 * @property-read string $size
 * @property-read string $src
 * @property-read int    $width
 * @property-read int    $height
 * @property-read bool   $is_intermediate
 * @property-read bool   $is_match
 *
 */
class Image_Derivative {
	/** @var string */
	private $size = '';

	/** @var string */
	private $src = '';

	/** @var int */
	private $width = 0;

	/** @var int */
	private $height = 0;

	/** @var bool */
	private $is_intermediate = false;

	/** @var bool */
	private $is_match = false;

	public function __construct( string $size = '', string $src = '', int $width = 0, int $height = 0, bool $is_intermediate = false, bool $is_match = false ) {
		$this->size            = $size;
		$this->src             = $src;
		$this->width           = $width;
		$this->height          = $height;
		$this->is_intermediate = $is_intermediate;
		$this->is_match        = $is_match;
	}

	public function __get( $key ) {
		if ( isset( $this->{$key} ) ) {
			return $this->{$key};
		}
		throw new \Exception( sprintf( 'Property %s does not exist', $key ) );
	}

	public function __set( $key, $value ) {
		throw new \Exception( sprintf( 'Instance of %s is immutable', __CLASS__ ) );
	}
}
