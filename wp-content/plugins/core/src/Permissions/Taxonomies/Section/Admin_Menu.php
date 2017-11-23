<?php


namespace Tribe\Project\Permissions\Taxonomies\Section;


class Admin_Menu {
	/**
	 * @return void
	 * @action admin_menu
	 */
	public function register_admin_menu() {
		$taxonomy = get_taxonomy( Section::NAME );
		$slug     = 'edit-tags.php?taxonomy=' . Section::NAME;
		add_menu_page( $taxonomy->label, $taxonomy->label, $taxonomy->cap->manage_terms, $slug, '', 'dashicons-networking' );
	}
}