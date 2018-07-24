<?php

namespace Tribe\Project\Components_Docs;

class Registry {

	/**
	 * @var Item[] $items
	 */
	protected $items = [];

	public function add_item( string $key, Item $item, string $group = 'Components' ) {
		$this->items[ $group ][ $key ] = $item;
	}

	public function get_items( $group = 'Components' ) {
		if ( 'all' === $group ) {
			return $this->items;
		}

		return $this->items[ $group ];
	}

	public function get_item( $key ) {
		foreach ( $this->items as $group => $items ) {
			if ( isset( $items[ $key ] ) ) {
				return $items[ $key ];
			}
		}

		return null;
	}
}