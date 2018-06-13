<?php

namespace Tribe\Project\Utils;


class Markup_Utils {

	public static function concat_attrs( array $attrs = null, $prefix = '' ) {
		if ( empty( $attrs ) ) {
			return '';
		}
		$out = [ ];
		$prefix = empty( $prefix ) ? '' : $prefix . '-';
		foreach ( $attrs as $key => $value ) {
			if ( is_array( $value ) ) {
				$out[] = self::concat_attrs( $value, $key );
			} else {
				$out[] = sprintf( '%s="%s"', $prefix . $key, $value );
			}
		}

		return implode( ' ', $out );
	}
}