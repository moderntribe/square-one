<?php


class Simple_301_Redirects_Migrator {
	public function needs_migration() {
		if ( $this->get_old_redirects() ) {
			return true;
		}
		return false;
	}

	public function migrate() {
		$old_redirects = $this->get_old_redirects();
		$new_redirects = $this->get_new_redirects();
		$new_redirects['string'] = array_merge( $old_redirects, $new_redirects['string'] );
		update_option( Tribe_301_Redirects::OPTION, $new_redirects );
		delete_option( '301_redirects' );
	}

	private function get_old_redirects() {
		$old_redirects = get_option( '301_redirects', array() );
		if ( !is_array( $old_redirects ) ) {
			return array();
		}
		return $old_redirects;
	}

	private function get_new_redirects() {
		$new_redirects = get_option( Tribe_301_Redirects::OPTION, array() );
		if ( !is_array( $new_redirects ) ) {
			$new_redirects = array();
		}
		if ( !isset( $new_redirects[ 'regex' ] ) ) {
			$new_redirects[ 'regex' ] = array();
		}

		if ( !isset( $new_redirects[ 'string' ] ) ) {
			$new_redirects[ 'string' ] = array();
		}
		return $new_redirects;
	}
}