<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Component;

class Page_Section extends Component {

	public const SUBHEADER = 'subheader';

	public function render(): void {
		?>
        {{ component( 'header/subheader/Subheader.php', subheader ) }}

		<div class="l-container s-sink t-sink">
			<h1>Sections</h1>
			<p>This is the standard "Layer cake" architecture. This markup can be used for panels or for other
				sections outside of the panel plugin.</p>
		</div>

		<div class="s-wrapper">
			<div class="l-container">
				<header class="s-header s-sink t-sink">
					<h2 class="site-section__title">Site Section 4 Column</h2>
					<div class="s-desc"><p>Site Section Description</p></div>
				</header>
				<div class="s-content s-sink t-sink">
					<div class="g-row g-row--center g-row--col-4--min-full">
						<div class="g-col">
							<div class="c-default">Component</div>
						</div>
						<div class="g-col">
							<div class="c-default">Component</div>
						</div>
						<div class="g-col">
							<div class="c-default">Component</div>
						</div>
						<div class="g-col">
							<div class="c-default">Component</div>
						</div>
					</div>
				</div>
				<footer class="s-footer">
					<a href="#" class="c-btn">CTA Button</a>
				</footer>
			</div>
		</div>

		<div class="s-wrapper">
			<div class="l-container">
				<header class="s-header s-sink t-sink">
					<h2 class="site-section__title">Site Section 2 Column</h2>
					<div class="s-desc"><p>Site Section Description</p></div>
				</header>
				<div class="s-content s-sink t-sink">
					<div class="g-row g-row--center g-row--col-2--min-full">
						<div class="g-col">
							<div class="c-default">Component</div>
						</div>
						<div class="g-col">
							<div class="c-default">Component</div>
						</div>
					</div>
				</div>
				<footer class="s-footer">
					<a href="#" class="c-btn-text">CTA Link</a>
				</footer>
			</div>
		</div>

		<div class="s-wrapper">
			<div class="l-container">
				<header class="s-header s-sink t-sink">
					<h2 class="site-section__title">Site Section Centered Component</h2>
					<div class="s-desc"><p>Site Section Description</p></div>
				</header>
				<div class="s-content s-sink t-sink">
					<div class="g-row g-row--center">
						<div class="g-col g-col--one-third">
							<div class="c-default">Component</div>
						</div>
					</div>
				</div>
				<footer class="s-footer">
					<a href="#" class="c-btn">CTA Button</a>
				</footer>
			</div>
		</div>

		<div class="s-wrapper">
			<header class="s-header s-sink t-sink">
				<h2 class="site-section__title">Site Section Full Width</h2>
				<div class="s-desc"><p>Site Section Description</p></div>
			</header>
			<div class="s-content s-sink t-sink">
				<div class="g-row g-row--no-gutters g-row--col-2--min-full">
					<div class="g-col">
						<div class="c-default">Component</div>
					</div>
					<div class="g-col">
						<div class="c-default">Component</div>
					</div>
				</div>
			</div>
			<footer class="s-footer">
				<a href="#" class="c-btn-text">CTA Link</a>
			</footer>
		</div>
		<?php
	}

}
