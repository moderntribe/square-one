<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Footer;

use Tribe\Project\Object_Meta\Social_Settings;
use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Site_Footer extends Component {
	use Copyright;

	public const NAVIGATION = 'navigation';
	public const SOCIAL     = 'social_follow';
	public const COPYRIGHT  = 'copyright';
	public const HOME_URL   = 'home_url';
	public const BLOG_NAME  = 'name';

	public function init() {
		$this->data[ self::SOCIAL ]    = $this->get_social_follow();
		$this->data[ self::COPYRIGHT ] = $this->get_copyright();
		$this->data[ self::HOME_URL ]  = home_url( '/' );
		$this->data[ self::BLOG_NAME ] = get_bloginfo( 'name' );
	}

	/**
	 * @return array
	 */
	protected function get_social_follow(): array {
		$links = [];

		// Change the order of this array to change the display order
		$social_keys = [
			Social_Settings::FACEBOOK,
			Social_Settings::TWITTER,
			Social_Settings::YOUTUBE,
			Social_Settings::LINKEDIN,
			Social_Settings::PINTEREST,
			Social_Settings::INSTAGRAM,
		];

		foreach ( $social_keys as $social_site ) {
			$social_link = get_field( $social_site, 'option' );

			if ( ! empty( $social_link ) ) {
				$links[ $social_site ] = [
					'url'   => $social_link,
					'title' => Social_Settings::get_social_follow_message( $social_site ),
				];
			}
		}

		return $links;
	}

	public function render(): void {
		?>
		<footer class="site-footer">

			<div class="l-container">

				{{ component( 'footer/navigation/Navigation.php', navigation ) }}

				{{ include( 'components/follow/follow.twig' ) }}

				<p>
					{{ copyright }}
					<a href="{{ home_url|esc_url }}" rel="external">
						{{ name }}
					</a>
				</p>

			</div>

		</footer>
		<?php
	}
}
