<?php

class WP_Export_File_Writer extends WP_Export_Base_Writer {
	private $f;
	private $file_name;

	public function __construct( $formatter, $file_name ) {
		parent::__construct( $formatter );
		$this->file_name = $file_name;
	}

	public function export() {
		$this->f = fopen( $this->file_name, 'w' );
		if ( !$this->f ) {
			throw new WP_Export_Exception( sprintf( __( 'WP Export: error opening %s for writing.' ), $this->file_name ) );
		}

		try { 
			parent::export();
		} catch ( WP_Export_Exception $e ) {
			throw $e;
		} catch ( WP_Export_Term_Exception $e ) {
			throw $e;
		}

		fclose( $this->f );
	}

	protected function write( $xml ) {
		$res = fwrite( $this->f, $xml);
		if ( false === $res ) {
			throw new WP_Export_Exception( __( 'WP Export: error writing to export file.' ) );
		}
	}
}
