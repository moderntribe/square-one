<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Content;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Share;

class Single extends Component {

	public const POST  = 'post';
	public const SHARE = 'social_share';

	public function init() {
		$this->data[ self::SHARE ] = $this->get_social_share();
	}

	public function get_social_share() {
		return [
			Share::LABELED => true,
		];
	}

	public function render(): void {
		?>
        <article class="item-single">

            {{ post.image }}

            <div class="item-single__content s-sink t-sink">
                {{ post.content }}
            </div>

            <footer class="item-single__footer">

                <ul class="item-single__meta">

                    <li class="item-single__meta-date">
                        <time datetime="">
                            {{ post.date|esc_html }}
                        </time>
                    </li>

                    <li class="item-single__meta-author">
                        {{ __('by')|esc_html }}
                        <a href="{{ post.author.url|esc_url }}" rel="author">
                            {{ post.author.name|esc_html }}
                        </a>
                    </li>

                </ul>

                {{ component( 'share/Share.php', social_share ) }}

            </footer>

        </article>
		<?php
	}
}
