<?php

class Git_Info_Plugin {
	/** @var Git_Info_Plugin */
	private static $instance;

	public function print_version_info() {
		if ( ! ($revision = $this->get_revision()) ) {
			return '';
		}
		$timestamp = $this->get_timestamp($revision);
		$branch = $this->get_branch();
		$branch = wp_basename($branch);

		$output = '<span class="git-info-admin">';
		$output .= __('Git Info: ','git-info');

		if ( $branch ) {
			$output .= sprintf( __( 'On branch %s', 'git-info' ), $branch );
			$output .= " &mdash; ";
		}

		if ( $timestamp ) {
			$output .= date('Y-m-d H:i:s', (int)$timestamp);
			$output .= " &mdash; ";
		}

		$output .= sprintf('<abbr title="%s">%s</abbr>', $revision, substr($revision, 0, 8));
		$output .= '</span>';
		return $output;
	}

	public function get_timestamp( $revision = '' ) {
		if ( !function_exists('exec') ) {
			return FALSE;
		}
		if ( !$revision ) {
			$revision = $this->get_revision();
		}
		$cmd = sprintf("git show --pretty=format:%%ct --summary %s", $revision);
		$output = array();
		exec($cmd, $output);
		if ( isset( $output[0] ) ) return $output[0];
	}

	public function get_revision() {
		$info = $this->get_info();
		return empty($info['revision'])?'':$info['revision'];
	}

	public function get_branch() {
		$info = $this->get_info();
		return empty($info['branch'])?'':$info['branch'];
	}

	private function get_info() {
		static $info = array();
		if ( $info ) {
			return $info;
		}
		$head_file = ABSPATH.DIRECTORY_SEPARATOR.'.git/HEAD';
		if ( !file_exists(ABSPATH.DIRECTORY_SEPARATOR.'.git/HEAD') ) {
			return $info;
		}
		$branch = trim(file_get_contents($head_file));
		$branch = str_replace('ref: ', '', $branch);
		$revision_file = ABSPATH.'.git'.DIRECTORY_SEPARATOR.$branch;
		$revision = file_get_contents($revision_file);
		$info = array(
			'branch' => rtrim($branch),
			'revision' => rtrim($revision),
		);
		return $info;
	}

	private function add_hooks() {
		add_filter('admin_footer_text', array($this, 'print_version_info'));
	}

	/********** Singleton *************/

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		self::$instance = self::get_instance();
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Git_Info_Plugin
	 */
	public static function get_instance() {
		if ( !is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	final public function __clone() {
		trigger_error( "No cloning allowed!", E_USER_ERROR );
	}

	final public function __sleep() {
		trigger_error( "No serialization allowed!", E_USER_ERROR );
	}

	protected function __construct() {
		$this->add_hooks();
	}
}
