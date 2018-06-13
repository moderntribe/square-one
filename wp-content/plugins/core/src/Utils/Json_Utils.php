<?php

namespace Tribe\Project\Utils;


class Json_Utils {

	public static function utf8_encode_recursive( $data ) {
		if ( empty( $data ) ) {
			return $data;
		}
		$result = [ ];
		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				$result[ $key ] = self::utf8_encode_recursive( $value );
			} elseif ( is_object( $value ) ) {
				$result[ $key ] = (object)self::utf8_encode_recursive( (array)$value );
			} else if ( is_string( $value ) ) {
				$result[ $key ] = self::utf8_encode_string( $value );
			} else {
				$result[ $key ] = $value;
			}
		}

		return $result;
	}

	protected static function utf8_encode_string( $string ) {
		$encoding = mb_detect_encoding( $string, "UTF-8, ISO-8859-1, ISO-8859-15", true );
		$string = mb_convert_encoding( $string, 'UTF-8', $encoding );
		return $string;
	}
}