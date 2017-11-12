<?php

namespace Tribe\Project\Queues\Tasks;

class Email implements Task {

	public function handle( array $args ) {
		wp_mail( ... );
	}
}