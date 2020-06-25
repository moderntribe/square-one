<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

class Masthead extends Component {
	public const NAVIGATION = 'navigation';
	public const LOGO       = 'logo';
	public const SEARCH     = 'search';

	public function init() {
		$this->data['logo']   = $this->get_logo();
		$this->data['search'] = $this->get_search();
	}

	protected function get_logo() {
		return sprintf(
				'<div class="logo" data-js="logo"><a href="%s" rel="home">%s</a></div>',
				esc_url( home_url() ),
				get_bloginfo( 'blogname' )
		);
	}

	protected function get_search() {
		return '';
	}

	public function render(): void {
		?>
		<header class="site-header">

			<a
					href="#main-content"
					class="a11y-skip-link u-visually-hidden"
			>
				{{ __( 'Skip to main content' )|esc_html }}
			</a>

			<div class="l-container">

				{{ logo }}

				{{ component( 'header/navigation/Navigation.php', navigation ) }}

				{{ search }}

			</div>

		</header>
		<?php
	}
}
