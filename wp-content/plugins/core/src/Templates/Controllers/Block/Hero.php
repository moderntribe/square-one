<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Project\Blocks\Types\Accordion as Accordion_Block;
use Tribe\Project\Blocks\Types\Accordion_Section as Accordion_Section_Block;
use Tribe\Project\Templates\Components\Accordion as Accordion_Component;
use Tribe\Project\Templates\Components\Panels\Accordion as Accordion_Context;

class Hero extends Block_Controller {
	public function render( string $path = '' ): string {
		$hero_style_tag = ! empty( $this->attributes[ 'icon' ] ) && ! empty( $this->attributes[ 'icon' ][ 'url' ] ) ? ' style="background-image: url(' .$this->attributes[ 'icon' ][ 'url' ]  . ')"' : '';
		$title = ! empty( $this->attributes[ 'title' ] ) ? '<h1 class="test-hero__title">' . $this->attributes[ 'title' ] . '</h1>' : '';
		$description = ! empty( $this->attributes[ 'description' ] ) ? '<div class="test-hero__description">' . $this->attributes[ 'description' ] . '</div>' : '';
		$cta = ! empty( $this->attributes[ 'cta' ] ) && ! empty( $this->attributes[ 'cta' ][ 'text' ] ) ? '<a class="test-hero__cta" href="' . $this->attributes[ 'cta' ][ 'text' ] . '">Click Here for More Clicks</a>' : '';
		return '
			<div class="test-hero"' . $hero_style_tag . '>
				<div class="test-hero__content content-wrap t-content t-content--light">
					' . $title . '
					' . $description . '
					' . $cta . '
				</div>
			</div>
';
	}
}
