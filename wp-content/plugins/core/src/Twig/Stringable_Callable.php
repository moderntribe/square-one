<?php


namespace Tribe\Project\Twig;

/**
 * Class Stringable_Callable
 *
 * A wrapper around a method/closure that implements
 * the __toString() method, allowing the callable to
 * be used in a string context.
 */
class Stringable_Callable {
	private $callback;

	/**
	 * @param callable $callback A method that returns a string
	 */
	public function __construct( callable $callback ) {
		$this->callback = $callback;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string) call_user_func( $this->callback );
	}
}