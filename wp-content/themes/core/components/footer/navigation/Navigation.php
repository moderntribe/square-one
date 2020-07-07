<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Footer;

use Tribe\Project\Templates\Components\Component;

class Navigation extends Component {
	public const MENU = 'menu';

	public function render(): void {
		?>
		{% if menu %}

			<nav class="site-footer__nav" aria-labelledby="site-footer__nav-label">

				<h2 id="site-footer__nav-label" class="u-visually-hidden">{{ __( 'Secondary Navigation' )|esc_html }}</h2>

				<ol class="site-footer__nav-list">
					{{ menu }}
				</ol>

			</nav>

		{% endif %}
		<?php
	}
}
