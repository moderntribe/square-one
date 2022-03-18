<?php declare(strict_types=1);

namespace Tribe\Project\P2P;

use Tribe\Libs\Container\Abstract_Subscriber;

class P2P_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		$this->fix_p2p_title();
	}

	/**
	 * Fix P2P titles.
	 *
	 * @return void
	 */
	private function fix_p2p_title(): void {
		add_filter(
			'p2p_candidate_title',
			function ( ...$args ) {
				return $this->container->get( P2P_Filters::class )->fix_title( ...$args );
			},
			10,
			3
		);

		add_filter(
			'p2p_connected_title',
			function ( ...$args ) {
				return $this->container->get( P2P_Filters::class )->fix_title( ...$args );
			},
			10,
			3
		);
	}

}
