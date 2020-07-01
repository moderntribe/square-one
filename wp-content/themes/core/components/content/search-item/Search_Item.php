<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Component;

class Search_Item extends Component {
	public const POST = 'post';

	public function render(): void {
		?>
		<article class="item-loop item-loop--search item-loop--{{ post.post_type|esc_attr }}">

			<header class="item-loop__header">

				<h3 class="item-loop__title">
					<a href="{{ post.permalink|esc_url }}" rel="bookmark">
						{{ post.title }}
					</a>
				</h3>

			</header>

			{{ post.image }}

			{{ post.excerpt }}

		</article>
		<?php
	}
}
