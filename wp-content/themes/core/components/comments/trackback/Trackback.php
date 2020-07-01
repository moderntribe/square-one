<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Comments;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Context;

/**
 * Class Trackback
 *
 * @property int      $comment_id
 * @property string[] $classes
 * @property string[] $attr
 * @property string   $label
 * @property string   $author_link
 * @property string   $edit_link
 */
class Trackback extends Component {

	public const COMMENT_ID  = 'comment_id';
	public const CLASSES     = 'classes';
	public const ATTRIBUTES  = 'attr';
	public const LABEL       = 'label';
	public const AUTHOR_LINK = 'author_link';
	public const EDIT_LINK   = 'edit_link';

	protected function defaults(): array {
		return [
			self::COMMENT_ID  => 0,
			self::CLASSES     => [],
			self::ATTRIBUTES  => [],
			self::LABEL       => '',
			self::AUTHOR_LINK => '',
			self::EDIT_LINK   => '',
		];
	}

	public function init() {
		$this->data[ self::ATTRIBUTES ]['id'] = sprintf( 'comment-%d', $this->data[ self::COMMENT_ID ] );
	}

	public function render(): void {
		?>
		<li {{ attr|stringify }} {{ classes|stringify }}>
			<p>
				<strong>{{ label }}</strong>

				{{ author_link }}

				{% if edit_link %}
					<span class="comment__action-edit">
						{{ edit_link }}
					</span>
				{% endif %}
			</p>
		</li>
		<?php
	}

}
