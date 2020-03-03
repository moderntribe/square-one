<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Traits;

trait Copyright {
	protected function get_copyright() {
		return sprintf( __( '%s %d All Rights Reserved.', 'tribe' ), '&copy;', date( 'Y' ) );
	}
}
