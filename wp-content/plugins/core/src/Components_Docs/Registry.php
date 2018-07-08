<?php

namespace Tribe\Project\Components_Docs;

class Registry {

	/**
	 * @var Component_Item[] $items
	 */
	protected $items = [];

	public function add_item( string $key, Component_Item $item ) {
		$this->items[ $key ] = $item;
	}

	public function get_items() {
		return $this->items;
	}

	public function get_item( $key ) {
		return $this->items[ $key ] ?? null;
	}

}