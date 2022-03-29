<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\Traits;

trait With_Pagination_Helper {

	public function get_current_page(): int {
		return (int) get_query_var( 'paged', 1 );
	}

	public function is_page_one(): bool {
		return 1 === $this->get_current_page();
	}

}
