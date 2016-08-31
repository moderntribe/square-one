<?php


namespace JSONLD;

/**
 * Class Panel_Data_Extractor
 *
 * Gets searchable strings out of a panel
 *
 * @package JSONLD
 */
class Panel_Data_Extractor {
	/** @var \ModularContent\Panel */
	private $panel = null;

	public function __construct( \ModularContent\Panel $panel ) {
		$this->panel = $panel;
	}

	public function extract() {
		$type = $this->panel->get( 'type' );
		switch( $type ) {
			case 'hero':
				return $this->get_hero();
			case 'image_text':
				return $this->get_image_plus_text();
			case 'masonry':
				return $this->get_masonry();
			case 'quote':
				return $this->get_quote();
			case 'WYSIWYG':
				return $this->get_wysiwyg();
			case 'stats':
				return $this->get_stats();
			default:
				return $this->get_nothing();
		}
	}

	private function get_title() {
		$title = $this->panel->get( 'title' );
		if ( $title ) {
			$depth = $this->panel->get_depth();
			$tag = 'h' . ( $depth + 2 );
			return sprintf( "<%s>%s<%s/>\n", $tag, $title, $tag );
		}
		return '';
	}

	/**
	 * Gets the title and content from a PostQuacker field
	 *
	 * @param string $field_name
	 * @return string
	 */
	private function get_quacker( $field_name ) {
		$data = $this->panel->get( $field_name );
		if ( ! $data ) {
			return '';
		}
		$depth = $this->panel->get_depth();
		$tag = 'h' . ( $depth + 3 );
		$data = wp_parse_args( $data, array(
			'title' => '',
			'content' => '',
		));
		$value = '';
		if ( !empty( $data[ 'title' ] ) ) {
			$value .= sprintf( "<%s>%s<%s/>\n", $tag, $data['title'], $tag );
		}
		if ( !empty( $data[ 'content' ] ) ) {
			$value .= apply_filters( 'the_content', $data[ 'content' ] ) . "\n";
		}
		return $value;
	}

	private function get_nothing() {
		return '';
	}

	/**
	 * A utility method since the same pattern applies to several modules
	 *
	 * @param string $content_field The name of the field holding the content textarea
	 *
	 * @return string
	 */
	private function get_title_and_content( $content_field = 'content' ) {
		$title = $this->get_title();
		$content = $this->panel->get( $content_field );
		if ( $content ) {
			$content = apply_filters( 'the_content', $content );
		}
		return $title . $content . "\n";
	}

	private function get_repeater_content( $repeater_key = '', $content_field = 'content' ) {
		$value = '';
		$items = $this->panel->get( $repeater_key );
		foreach ( $items as $item ) {
			if ( !empty( $item[ $content_field ] ) ) {
				$content = is_array( $item[ $content_field ] )?  $item[ $content_field ]['content'] :  $item[ $content_field ];
				$value .= apply_filters( 'the_content', $content ) . "\n";
			}
		}
		return $value;
	}

	private function get_hero() {
		$value = $this->get_title();
		$value .= $this->get_repeater_content( 'heroes' );
		return $value;
	}

	private function get_image_plus_text() {
		$value = $this->get_title();
		$value .= $this->get_repeater_content( 'images', 'text' );
		return $value;
	}

	private function get_masonry() {
		$value = $this->get_title();
		$value .= $this->get_quacker( 'masonry_post' );
		return $value;
	}

	private function get_quote() {
		return $this->get_title_and_content( 'quote' );
	}

	private function get_wysiwyg() {
		$value = $this->get_title();
		$value .= $this->get_repeater_content( 'content' );
		return $value;
	}

	private function get_stats() {
		$value = $this->get_title();
		$value .= $this->get_repeater_content( 'statistics' );
		return $value;
	}
}
