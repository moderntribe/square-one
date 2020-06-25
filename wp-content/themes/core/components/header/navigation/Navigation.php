<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Header;

use Tribe\Project\Templates\Components\Component;

class Navigation extends Component {
	public const MENU = 'menu';

	public function render(): void {
		if ( empty( $this->data['menu'] ) ) {
			return;
		}
		?>
		<nav class="site-header__nav" aria-labelledby="site-header__nav-label">

			<h2 id="site-header__nav-label" class="u-visually-hidden">{{ __( 'Primary Navigation' )|esc_html }}</h2>

			<ol class="site-header__nav-list">
				{{ menu }}
			</ol>

		</nav>
		<?php
	}
}
