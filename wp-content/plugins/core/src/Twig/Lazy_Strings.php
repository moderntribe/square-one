<?php


namespace Tribe\Project\Twig;

/**
 * Class Lazy_Strings
 *
 * A lazy-loading translating wrapper for strings.
 *
 * It will pass any requested string through __()
 * with the given text domain.
 *
 * @see __()
 */
class Lazy_Strings implements \ArrayAccess {
	private $strings = [];

	private $textdomain = 'tribe';

	public function __construct( $textdomain ) {
		$this->textdomain = $textdomain;
	}

	/**
	 * Whether a offset exists
	 *
	 * @param mixed $offset
	 * @return boolean true on success or false on failure.
	 */
	public function offsetExists( $offset ) {
		return true;
	}

	/**
	 * Offset to retrieve
	 * @param mixed $string
	 *
	 * @return string The translated string
	 */
	public function offsetGet( $string ) {
		if ( ! array_key_exists( $string, $this->strings ) ) {
			$this->strings[ $string ] = __( $string, $this->textdomain );
		}
		return $this->strings[ $string ];
	}

	/**
	 * Offset to set
	 *
	 * @param mixed $string
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet( $string, $value ) {
		$this->strings[ $string ] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->strings[ $offset ] );
	}

}