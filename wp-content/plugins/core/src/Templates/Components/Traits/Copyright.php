<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Traits;

trait Copyright {
	public function get_copyright(): string {
		return sprintf( __( '%s %d All Rights Reserved.', 'tribe' ), '&copy;', date( 'Y' ) );
	}
}
