<?php


namespace Tribe\Project\Blog_Copier;

/**
 * Class Copy_Configuration
 *
 * The configuration for a blog copy
 */
class Copy_Configuration implements \JsonSerializable {
	private $src     = 0;
	private $address = '';
	private $title   = '';
	private $files   = true;
	private $notify  = '';
	private $user    = 0;

	public function __construct( array $args = [] ) {
		foreach ( $args as $key => $value ) {
			if ( method_exists( $this, 'set_' . $key ) ) {
				call_user_func( [ $this, 'set_' . $key ], $value );
			}
		}
	}


	/**
	 * @return int
	 */
	public function get_src() {
		return $this->src;
	}

	/**
	 * @param int $src
	 */
	private function set_src( $src ) {
		$this->src = $src;
	}

	/**
	 * @return string
	 */
	public function get_address() {
		return $this->address;
	}

	/**
	 * @param string $address
	 */
	private function set_address( $address ) {
		$this->address = $address;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	private function set_title( $title ) {
		$this->title = $title;
	}

	/**
	 * @return bool
	 */
	public function get_files() {
		return $this->files;
	}

	/**
	 * @param bool $files
	 */
	private function set_files( $files ) {
		$this->files = $files;
	}

	/**
	 * @return string
	 */
	public function get_notify() {
		return $this->notify;
	}

	/**
	 * @param string $notify
	 */
	private function set_notify( $notify ) {
		$this->notify = $notify;
	}

	/**
	 * @return int
	 */
	public function get_user() {
		return $this->user;
	}

	/**
	 * @param int $user
	 */
	private function set_user( $user ) {
		$this->user = $user;
	}

	public function jsonSerialize() {
		return [
			'src'     => $this->get_src(),
			'address' => $this->get_address(),
			'title'   => $this->get_title(),
			'files'   => $this->get_files(),
			'notify'  => $this->get_notify(),
			'user'    => $this->get_user(),
		];
	}
}