<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Types\Logos;

use Tribe\Project\Blocks\Types\Base_Model;
use Tribe\Project\Templates\Models\Image;

class Model extends Base_Model {

	public function get_data(): array {
		return [
			'header' => $this->get_header(),
			'logos'  => $this->get_logos(),
		];
	}

	/**
	 * @return array
	 */
	private function get_cta(): array {
		$cta = wp_parse_args( $this->get( Logos::CTA, [] ), [
			'title'  => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			'content' => $cta['title'],
			'url'     => $cta['url'],
			'target'  => $cta['target'],
		];
	}

	/**
	 * @return array
	 */
	private function get_header(): array {
		return [
			'title'       => $this->get( Logos::TITLE, '' ),
			'description' => $this->get( Logos::DESCRIPTION, '' ),
			'cta'         => $this->get_cta(),
		];
	}

	/**
	 * @return array
	 */
	private function get_logos(): array {
		$logos      = [];
		$logos_data = $this->get( Logos::LOGOS, false );

		if ( empty( $logos_data ) ) {
			return $logos;
		}

		foreach ( $this->get( Logos::LOGOS, false ) as $logo ) {
			// Don't add a logo if there's no image set in the block.
			if ( empty( $logo[ Logos::LOGO_IMAGE ] ) ) {
				continue;
			}

			$logos[]['attachment'] = Image::factory( (int) $logo[ Logos::LOGO_IMAGE ]['id'] );
			$logos[]['link'] = Image::factory( (int) $logo[ Logos::LOGO_IMAGE ]['id'] );
		}

		return $logos;
	}

	/**
	 * @param array $logo
	 *
	 * @return array
	 */
	private function get_single_logo( array $logo ): array {
		$logo = [
			'attachment'      => Image::factory( (int) $logo[ Logos::LOGO_IMAGE ]['id'] ),
			//'use_lazyload'    => true,
			//'wrapper_classes' => [ 'b-logo__figure' ],
			//'img_classes'     => [ 'b-logo__img' ],
			//'src_size'        => 'large',
			//'srcset_sizes'    => [ 'medium', 'large' ],
		];

		$link = wp_parse_args( $logo[ Logos::LOGO_LINK ], [
			'title'   => '',
			'url'    => '',
			'target' => '',
		] );

		return [
			'content' => $cta['title'],
			'url'     => $cta['url'],
			'target'  => $cta['target'],
		];

		if ( ! empty( $link['url'] ) ) {
			//$logo['link_url']     = $link['url'];
			//$logo['link_target']  = $link['target'];
			//$logo['link_classes'] = [ 'b-logo__link' ];
			//$logo['link_attrs']   = ! empty( $link['text'] ) ? [ 'aria-label' => $link['text'] ] : [];
		}

		return $logo;
	}
}
