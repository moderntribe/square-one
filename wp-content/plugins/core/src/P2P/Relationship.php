<?php


namespace Tribe\Project\P2P;


abstract class Relationship {
	const NAME = ''; // override this in a child class

	/**
	 * @var array Object types on the from side of the relationship
	 */
	protected $from = [];

	/**
	 * @var array Object types on the to side of the relationship
	 */
	protected $to = [];

	public function __construct( $from = [ ], $to = [ ] ) {
		$this->from = $from;
		$this->to = $to;
	}

	public function hook() {
		add_action( 'p2p_init', [ $this, 'register' ], 10, 0 );
	}

	public function register() {
		p2p_register_connection_type( $this->get_all_args() );
	}

	private function get_all_args() {
		return wp_parse_args( $this->get_args(), [
			'id' => static::NAME,
			'from' => $this->from,
			'to' => $this->to,
		] );
	}

	public function from() {
		return $this->from;
	}

	public function to() {
		return $this->to;
	}

	abstract protected function get_args();
}