<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Component;

class Page_Example_App extends Component {

	public const SUBHEADER = 'subheader';

	public function render(): void {
		?>
        {{ component( 'header/subheader/Subheader.php', subheader ) }}

        <div data-js="example-app" class="l-container s-sink t-sink">

        </div>
		<?php
	}

}
