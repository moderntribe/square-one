<?php


namespace JSONLD;


class Data_Printer {
	public function print_data( $data ) {
		$data = json_encode( $data, JSON_PRETTY_PRINT );
		if ( $data ) {
			echo '<script type="application/ld+json">';
			echo $data;
			echo '</script>';
		}
	}
}