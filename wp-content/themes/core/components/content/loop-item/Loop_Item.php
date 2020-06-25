<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Component;

class Loop_Item extends Component {

	public const POST = 'post';

	public function render(): void {
		?>
		<article class="item-loop item-loop--{{ post.post_type }}">

			<header class="item-loop__header">

				<h3 class="item-loop__title">
					<a href="{{ post.permalink }}" rel="bookmark">
						{{ post.title }}
					</a>
				</h3>

			</header>

			{{ post.image }}

			{{ post.excerpt }}

			<footer class="item-loop__footer">

				<ul class="item-loop__meta">

					<li class="item-loop__meta-date">
						<time datetime="{{ post.time['c'] }}">
							{{ post.time['F j, Y'] }}
						</time>
					</li>

					<li class="item-loop__meta-author">
						{{ __( 'by' )|esc_html }}
						<a href="{{ post.author.url }}" rel="author">
							{{ post.author.name }}
						</a>
					</li>

				</ul>

			</footer>

		</article>

		<?php
	}

}
