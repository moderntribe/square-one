<?php

namespace Tribe\Project\Templates\Components;

/**
 * Class Quote
 *
 * @property string   $quote
 * @property string   $cite
 * @property string[] $classes
 * @property string[] $quote_attrs
 * @property string[] $cite_attrs
 */
class Quote extends Component {

	public const QUOTE       = 'quote';
	public const CITE        = 'cite';
	public const CLASSES     = 'classes';
	public const QUOTE_ATTRS = 'quote_attrs';
	public const CITE_ATTRS  = 'cite_attrs';

	protected function defaults(): array {
		return [
			self::QUOTE       => '',
			self::CITE        => '',
			self::CLASSES     => [ 'c-quote' ],
			self::QUOTE_ATTRS => [],
			self::CITE_ATTRS  => [],
		];
	}

	public function render(): void {
		?>
		<blockquote {{ classes }}>
			<p class="c-quote__text" {{ quote_attrs }}>{{ quote }}</p>
			{% if cite %}
				<cite class="c-quote__cite" {{ cite_attrs }}>{{ cite }}</cite>
			{% endif %}
		</blockquote>
		<?php
	}
}
