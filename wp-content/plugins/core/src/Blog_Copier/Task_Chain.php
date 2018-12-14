<?php


namespace Tribe\Project\Blog_Copier;


class Task_Chain {
	/** @var string[] */
	private $tasks = [];

	private $first = '';

	public function __construct( array $steps = [] ) {
		$prev = '';
		foreach ( $steps as $next ) {
			if ( $prev ) {
				$this->set_step( $prev, $next );
			} else {
				$this->set_first( $next );
			}
			$prev = $next;
		}
	}

	public function set_first( $step ) {
		$this->first = $step;
	}

	public function get_first() {
		return $this->first;
	}

	/**
	 * @param string $prev
	 * @param string $next
	 *
	 * @return void
	 */
	public function set_step( $prev, $next ) {
		$this->tasks[ $prev ] = $next;
	}

	/**
	 * Insert a step between existing steps, without removing any
	 *
	 * @param string $prev
	 * @param string $next
	 *
	 * @return void
	 */
	public function insert_step( $prev, $next ) {
		$old = $this->get_next( $prev );
		$this->set_step( $prev, $next );
		if ( $old ) {
			$this->set_step( $next, $old );
		}
	}

	/**
	 * @param $prev
	 *
	 * @return string
	 */
	public function get_next( $prev ) {
		if ( array_key_exists( $prev, $this->tasks ) ) {
			return $this->tasks[ $prev ];
		}
		return '';
	}
}