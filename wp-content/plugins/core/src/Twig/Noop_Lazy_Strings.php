<?php


namespace Tribe\Project\Twig;


class Noop_Lazy_Strings extends Lazy_Strings {
	/**
	 * Skip the translation step when you know the site
	 * is monolingual.
	 *
	 * @param mixed $string
	 *
	 * @return string The translated string
	 */
	public function offsetGet( $string ) {
		return $string;
	}

}