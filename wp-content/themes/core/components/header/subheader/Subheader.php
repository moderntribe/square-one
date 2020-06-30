<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Header;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Context;

class Subheader extends Component {
	public const TITLE = 'title';

	public function render(): void {
		if ( empty( $this->data['title'] ) ) {
			return;
		}
		?>
		<header>

			<div class="l-container">

				{{ component( 'text/Text.php', title ) }}

			</div>

		</header>
		<?php
	}
}
