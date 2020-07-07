<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

class Main extends Component {
	public const CONTENT       = 'content';
	public const TEMPLATE_TYPE = 'template_type';

	public function render(): void {
		?>
		<main id="main-content">

			{{ component( template_type, content ) }}

		</main>
		<?php
	}
}
