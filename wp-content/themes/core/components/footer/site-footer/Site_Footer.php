<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Footer;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Site_Footer extends Component {
	use Copyright;

	public const NAVIGATION = 'navigation';
	public const COPYRIGHT  = 'copyright';
	public const HOME_URL   = 'home_url';
	public const BLOG_NAME  = 'name';

	public function init() {
		$this->data[ self::COPYRIGHT ] = $this->get_copyright();
		$this->data[ self::HOME_URL ]  = home_url( '/' );
		$this->data[ self::BLOG_NAME ] = get_bloginfo( 'name' );
	}
}
