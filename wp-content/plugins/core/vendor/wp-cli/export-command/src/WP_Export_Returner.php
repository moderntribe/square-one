<?php

class WP_Export_Returner extends WP_Export_Base_Writer {
	private $result = '';

	public function export() {
		$this->private = '';
		try { 
			parent::export();
		} catch ( WP_Export_Exception $e ) {
			$message = apply_filters( 'export_error_message', $e->getMessage() );
			return new WP_Error( 'wp-export-error', $message );
			
		} catch ( WP_Export_Term_Exception $e ) {
			do_action( 'export_term_orphaned', $this->formatter->export->missing_parents );
			$message = apply_filters( 'export_term_error_message', $e->getMessage() );
			return new WP_Error( 'wp-export-error', $message );
		}
		return $this->result;
	}
	protected function write( $xml ) {
		$this->result .= $xml;
	}
}
