<?php

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Component;

class No_Results extends Component {

	public function render(): void {
		?>
		<aside class="no-results">

			<h3 class="no-results__title">{{ __( 'No Posts' )|esc_html }}</h3>

			<p class="no-results__content">{{ __( 'Sorry, but there are currently no posts to see at this time.' )|esc_html }}</p>

		</aside>
		<?php
	}
}
