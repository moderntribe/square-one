<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

/*
 * @TODO: THIS IS NOT COMPLETE AND WILL BE PICKED BACK UP BY RYAN
 */

class Hero extends Block_Controller {
	public function render( string $path = '' ): string {
		$title = ! empty( $this->attributes[ 'title' ] ) ? '<h1 class="test-hero__title h1">' . $this->attributes[ 'title' ] . '</h1>' : '<h1 class="test-hero__title h1">A Heroic Title</h1>';
		$description = ! empty( $this->attributes[ 'description' ] ) ? '<div class="test-hero__description">' . $this->attributes[ 'description' ] . '</div>' : '';
		$cta = ! empty( $this->attributes[ 'cta' ] ) && ! empty( $this->attributes[ 'cta' ][ 'url' ] ) ? '<a class="test-hero__cta btn-submit" href="' . $this->attributes[ 'cta' ][ 'url' ] . '">' . $this->attributes[ 'cta' ][ 'text' ] . '</a>' : '';
		$leadin = ! empty( $this->attributes[ 'lead-in' ] )
			? '<span class="test-hero__leadin">' . $this->attributes[ 'lead-in' ] . '</span>'
			: '<span class="test-hero__leadin">Some Lead In Text</span>';

		$hero_bg = ! empty( $this->attributes[ 'bg-image' ] ) && ! empty( $this->attributes[ 'bg-image' ][ 'url' ] )
			? 'background-image: url(' . $this->attributes[ 'bg-image' ][ 'url' ]  . ');'
			: '';

		$align = ! empty( $this->attributes[ 'align' ] )
			? 'display: flex; align-items:' . $this->attributes[ 'align' ] . '; justify-content:' . $this->attributes[ 'align' ] . ';'
			: '';


		return '
			<div class="test-hero" style="' . $hero_bg . $align . '">
				<div class="test-hero__content t-sink t-theme--light">
					' . $leadin . '
					' . $title . '
					' . $description . '
					' . $cta . '
				</div>
			</div>
';
	}
}
