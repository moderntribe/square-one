<?php
declare( strict_types=1 );

namespace Tribe\Project\Models;

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
class Image_Derivative extends Model {

	/** @var string */
	protected $size = '';

	/** @var string */
	protected $src = '';

	/** @var int */
	protected $width = 0;

	/** @var int */
	protected $height = 0;

	/** @var bool */
	protected $is_intermediate = false;

	/** @var bool */
	protected $is_match = false;

	/**
	 * Image_Derivative constructor.
	 *
	 * @param string $size
	 * @param string $src
	 * @param int    $width
	 * @param int    $height
	 * @param bool   $is_intermediate
	 * @param bool   $is_match
	 */
	public function __construct( string $size = '', string $src = '', int $width = 0, int $height = 0, bool $is_intermediate = false, bool $is_match = false ) {
		$this->size            = $size;
		$this->src             = $src;
		$this->width           = $width;
		$this->height          = $height;
		$this->is_intermediate = $is_intermediate;
		$this->is_match        = $is_match;
	}
}
